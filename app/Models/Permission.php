<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $primaryKey = 'permission_id';

    protected $fillable = [
        'permission_name',
        'module',
        'action',
        'display_name',
        'description',
    ];

    /**
     * A permission belongs to many roles through role_permissions.
     * Your teammate's Role model will use this inverse.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions',
            'permission_id',
            'role_id',
            'permission_id',
            'role_id'
        );
    }
}
