<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Role;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    /** @test */
    public function admin_can_create_short_urls()
    {
        $company = Company::create(['name' => 'Test Company', 'slug' => 'test-company']);
        $adminRole = Role::where('name', Role::ADMIN)->first();

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
            'role_id' => $adminRole->id,
        ]);

        $response = $this->actingAs($admin)->post(route('short-urls.store'), [
            'original_url' => 'https://example.com/long/url',
        ]);

        $response->assertRedirect(route('short-urls.index'));
        $this->assertDatabaseHas('short_urls', [
            'user_id' => $admin->id,
            'company_id' => $company->id,
            'original_url' => 'https://example.com/long/url',
        ]);
    }

    #[Test]
    public function member_can_create_short_urls()
    {
        $company = Company::create(['name' => 'Test Company', 'slug' => 'test-company']);
        $memberRole = Role::where('name', Role::MEMBER)->first();

        $member = User::create([
            'name' => 'Member User',
            'email' => 'member@test.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
            'role_id' => $memberRole->id,
        ]);

        $response = $this->actingAs($member)->post(route('short-urls.store'), [
            'original_url' => 'https://example.com/long/url',
        ]);

        $response->assertRedirect(route('short-urls.index'));
        $this->assertDatabaseHas('short_urls', [
            'user_id' => $member->id,
        ]);
    }

    #[Test]
    public function super_admin_cannot_create_short_urls()
    {
        $superAdminRole = Role::where('name', Role::SUPER_ADMIN)->first();

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $superAdminRole->id,
        ]);

        $response = $this->actingAs($superAdmin)->post(route('short-urls.store'), [
            'original_url' => 'https://example.com/long/url',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseCount('short_urls', 0);
    }

    #[Test]
    public function admin_can_only_see_urls_created_in_their_company()
    {
        $company1 = Company::create(['name' => 'Company 1', 'slug' => 'company-1']);
        $company2 = Company::create(['name' => 'Company 2', 'slug' => 'company-2']);

        $adminRole = Role::where('name', Role::ADMIN)->first();
        $memberRole = Role::where('name', Role::MEMBER)->first();

        $admin1 = User::create([
            'name' => 'Admin 1',
            'email' => 'admin1@test.com',
            'password' => bcrypt('password'),
            'company_id' => $company1->id,
            'role_id' => $adminRole->id,
        ]);

        $member2 = User::create([
            'name' => 'Member 2',
            'email' => 'member2@test.com',
            'password' => bcrypt('password'),
            'company_id' => $company2->id,
            'role_id' => $memberRole->id,
        ]);

        // Create URLs in both companies
        $url1 = ShortUrl::create([
            'user_id' => $admin1->id,
            'company_id' => $company1->id,
            'original_url' => 'https://example.com/1',
            'short_code' => 'abc123',
        ]);

        $url2 = ShortUrl::create([
            'user_id' => $member2->id,
            'company_id' => $company2->id,
            'original_url' => 'https://example.com/2',
            'short_code' => 'def456',
        ]);

        $response = $this->actingAs($admin1)->get(route('short-urls.index'));

        $response->assertStatus(200);
        $response->assertSee('abc123');
        $response->assertDontSee('def456');
    }

    /** @test */
    public function member_can_only_see_urls_created_by_themselves()
    {
        $company = Company::create(['name' => 'Test Company', 'slug' => 'test-company']);
        $memberRole = Role::where('name', Role::MEMBER)->first();

        $member1 = User::create([
            'name' => 'Member 1',
            'email' => 'member1@test.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
            'role_id' => $memberRole->id,
        ]);

        $member2 = User::create([
            'name' => 'Member 2',
            'email' => 'member2@test.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
            'role_id' => $memberRole->id,
        ]);

        // Create URLs by different members
        $url1 = ShortUrl::create([
            'user_id' => $member1->id,
            'company_id' => $company->id,
            'original_url' => 'https://example.com/1',
            'short_code' => 'abc123',
        ]);

        $url2 = ShortUrl::create([
            'user_id' => $member2->id,
            'company_id' => $company->id,
            'original_url' => 'https://example.com/2',
            'short_code' => 'def456',
        ]);

        $response = $this->actingAs($member1)->get(route('short-urls.index'));

        $response->assertStatus(200);
        $response->assertSee('abc123');
        $response->assertDontSee('def456');
    }

    #[Test]
    public function short_urls_are_publicly_resolvable_and_redirect()
    {
        $company = Company::create(['name' => 'Test Company', 'slug' => 'test-company']);
        $memberRole = Role::where('name', Role::MEMBER)->first();

        $member = User::create([
            'name' => 'Member User',
            'email' => 'member@test.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
            'role_id' => $memberRole->id,
        ]);

        $shortUrl = ShortUrl::create([
            'user_id' => $member->id,
            'company_id' => $company->id,
            'original_url' => 'https://example.com/destination',
            'short_code' => 'abc123',
            'clicks' => 0,
        ]);

        // Test redirect without authentication
        $response = $this->get('/' . $shortUrl->short_code);

        $response->assertRedirect('https://example.com/destination');

        // Verify clicks were incremented
        $this->assertEquals(1, $shortUrl->fresh()->clicks);
    }

    #[Test]
    public function super_admin_can_see_all_short_urls_from_all_companies()
    {
        $company1 = Company::create(['name' => 'Company 1', 'slug' => 'company-1']);
        $company2 = Company::create(['name' => 'Company 2', 'slug' => 'company-2']);

        $superAdminRole = Role::where('name', Role::SUPER_ADMIN)->first();
        $memberRole = Role::where('name', Role::MEMBER)->first();

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $superAdminRole->id,
        ]);

        $member1 = User::create([
            'name' => 'Member 1',
            'email' => 'member1@test.com',
            'password' => bcrypt('password'),
            'company_id' => $company1->id,
            'role_id' => $memberRole->id,
        ]);

        $member2 = User::create([
            'name' => 'Member 2',
            'email' => 'member2@test.com',
            'password' => bcrypt('password'),
            'company_id' => $company2->id,
            'role_id' => $memberRole->id,
        ]);

        // Create URLs in both companies
        ShortUrl::create([
            'user_id' => $member1->id,
            'company_id' => $company1->id,
            'original_url' => 'https://example.com/1',
            'short_code' => 'abc123',
        ]);

        ShortUrl::create([
            'user_id' => $member2->id,
            'company_id' => $company2->id,
            'original_url' => 'https://example.com/2',
            'short_code' => 'def456',
        ]);

        $response = $this->actingAs($superAdmin)->get(route('short-urls.index'));

        $response->assertStatus(200);
        $response->assertSee('abc123');
        $response->assertSee('def456');
    }
}
