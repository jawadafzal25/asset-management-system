<?php

namespace App\Services;

use App\Models\Permission;
use App\Repositories\PermissionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionService
{
    protected PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Return all permissions grouped by module.
     */
    public function allGroupedByModule(): \Illuminate\Support\Collection
    {
        return $this->permissionRepository->allGroupedByModule();
    }

    /**
     * Paginated flat list for table view.
     */
    public function paginated(int $perPage = 20): LengthAwarePaginator
    {
        return $this->permissionRepository->paginated($perPage);
    }

    /**
     * Get permissions for a specific module only.
     */
    public function byModule(string $module): Collection
    {
        return $this->permissionRepository->byModule($module);
    }

    /**
     * Get a single permission by ID.
     * Throws ModelNotFoundException if not found — caught by global handler.
     */
    public function findById(int $id): Permission
    {
        $permission = $this->permissionRepository->findById($id);

        throw_if(
            !$permission,
            \Illuminate\Database\Eloquent\ModelNotFoundException::class,
            "Permission with ID {$id} not found."
        );

        return $permission;
    }

    /**
     * Get all available module names for the filter dropdown.
     */
    public function allModules(): array
    {
        return $this->permissionRepository->allModules();
    }

    /**
     * Validate that a set of permission IDs all exist in the database.
     * Called by teammate's RoleService before attaching permissions to a role.
     *
     * Uses data_get() to safely pull permission_id from each result object.
     */
    public function validatePermissionIds(array $permissionIds): Collection
    {
        $permissions = $this->permissionRepository->findManyByIds($permissionIds);

        // Use data_get() to extract permission_id from each Permission object
        // instead of: $permissions->pluck('permission_id')->toArray()
        $foundIds = [];
        foreach ($permissions as $permission) {
            $foundIds[] = data_get($permission, 'permission_id');
        }

        $invalidIds = array_diff($permissionIds, $foundIds);

        throw_if(
            count($invalidIds) > 0,
            \InvalidArgumentException::class,
            'Invalid permission IDs: ' . implode(', ', $invalidIds)
        );

        return $permissions;
    }
}
