<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'password'  => 'hashed',
        'is_active' => 'boolean',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(UserToken::class, 'user_id', 'user_id');
    }

    /**
     * Check if this user's role has a specific permission.
     * e.g. $user->hasPermission('employee.create')
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->role?->hasPermission($permissionName) ?? false;
    }

    /**
     * Main admin check — role_id is null means they are the root admin.
     */
    public function isMainAdmin(): bool
    {
        return $this->role_id === null;
    }
}
