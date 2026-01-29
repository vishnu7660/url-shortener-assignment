<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public const SUPER_ADMIN = 'SuperAdmin';
    public const ADMIN = 'Admin';
    public const MEMBER = 'Member';
    public const SALES = 'Sales';
    public const MANAGER = 'Manager';

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
