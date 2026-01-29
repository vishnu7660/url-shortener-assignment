<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Get SuperAdmin role id using raw SQL
        $superAdminRole = DB::select("SELECT id FROM roles WHERE name = 'SuperAdmin' LIMIT 1");

        if (empty($superAdminRole)) {
            $this->command->error('SuperAdmin role not found. Please run RoleSeeder first.');
            return;
        }

        $roleId = $superAdminRole[0]->id;

        // Insert SuperAdmin user using raw SQL
        DB::insert(
            "INSERT INTO users (name, email, password, role_id, company_id, email_verified_at, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                'Super Admin',
                'superadmin@example.com',
                Hash::make('password'),
                $roleId,
                null, // SuperAdmin doesn't belong to any company
                now(),
                now(),
                now()
            ]
        );

        $this->command->info('SuperAdmin created successfully!');
        $this->command->info('Email: superadmin@example.com');
        $this->command->info('Password: password');
    }
}
