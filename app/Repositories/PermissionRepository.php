<?php

namespace App\Repositories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionRepository
{
    /**
     * Return all permissions grouped by module.
     * Used by frontend to render tick-box groups per module.
     */
    public function allGroupedByModule(): \Illuminate\Support\Collection
    {
        $all = Permission::all();

        // group into [ 'Employee Management' => [...], 'Asset Management' => [...] ]
        $grouped = $all->groupBy('module');

        // re-index each group so keys are 0,1,2... not original collection keys
        return $grouped->map(function ($group) {
            return $group->values();
        });
    }

    /**
     * Paginated flat list — for admin management screens.
     */
    public function paginated(int $perPage = 20): LengthAwarePaginator
    {
        return Permission::orderBy('module')
            ->orderBy('action')
            ->paginate($perPage);
    }

    /**
     * Get permissions filtered by module name.
     */
    public function byModule(string $module): Collection
    {
        return Permission::where('module', $module)
            ->orderBy('action')
            ->get();
    }

    /**
     * Find a permission by ID.
     */
    public function findById(int $id): ?Permission
    {
        return Permission::find($id);
    }

    /**
     * Find a permission by its unique name.
     */
    public function findByName(string $permissionName): ?Permission
    {
        return Permission::where('permission_name', $permissionName)->first();
    }

    /**
     * Get multiple permissions by array of IDs.
     * Used by teammate's RoleService when attaching permissions to a role.
     */
    public function findManyByIds(array $ids): Collection
    {
        return Permission::whereIn('permission_id', $ids)->get();
    }

    /**
     * Get all distinct module names.
     * Used to populate module filter dropdown on frontend.
     */
    public function allModules(): array
    {
        return Permission::distinct()
            ->orderBy('module')
            ->pluck('module')
            ->toArray();
    }
}
