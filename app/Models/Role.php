<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'slug', 
        'description',
        'is_active'
    ];

    /**
     * Permissions attached to this role via role_permissions pivot.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',
            'permission_id',
            'id',
            'permission_id'
        );
    }

    /**
     * Users who have this role.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }

    /**
     * Helper: does this role have a specific permission?
     * e.g. $role->hasPermission('employee.create')
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions->contains('permission_name', $permissionName);
    }
}
