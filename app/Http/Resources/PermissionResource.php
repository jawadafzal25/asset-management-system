<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class PermissionResource extends JsonResource
{
    /**
     * Single permission — used in detail(), create()
     *
     * Called as: new PermissionResource($permission)
     *
     * Returns:
     * {
     *   "permission_id": 15,
     *   "permission_name": "employee.view",
     *   "module": "Employee Management",
     *   "action": "view",
     *   "display_name": "View Employees",
     *   "description": "Can view all employee records."
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'permission_id'   => data_get($this, 'permission_id'),
            'permission_name' => data_get($this, 'permission_name'),
            'module'          => data_get($this, 'module'),
            'action'          => data_get($this, 'action'),
            'display_name'    => data_get($this, 'display_name'),
            'description'     => data_get($this, 'description'),
            'created_at'      => data_get($this, 'created_at'),
        ];
    }

    /**
     * Grouped permissions — used in grouped()
     *
     * Called as: PermissionResource::grouped($collection)
     *
     * Returns:
     * {
     *   "Employee Management": [
     *     { "permission_id": 15, "permission_name": "employee.view", ... },
     *     { "permission_id": 16, "permission_name": "employee.create", ... }
     *   ],
     *   "Asset Management": [ ... ]
     * }
     */
    public static function grouped(Collection $groupedPermissions): array
    {
        $result = [];

        foreach ($groupedPermissions as $moduleName => $permissions) {
            $result[$moduleName] = self::formatGroup($permissions);
        }

        return $result;
    }

    /**
     * Format one module's permission list.
     */
    private static function formatGroup($permissions): array
    {
        $formatted = [];

        foreach ($permissions as $permission) {
            $formatted[] = self::formatSingle($permission);
        }

        return $formatted;
    }

    /**
     * Format a single permission using data_get().
     * Used inside grouped() only.
     */
    private static function formatSingle($permission): array
    {
        return [
            'permission_id'   => data_get($permission, 'permission_id'),
            'permission_name' => data_get($permission, 'permission_name'),
            'module'          => data_get($permission, 'module'),
            'action'          => data_get($permission, 'action'),
            'display_name'    => data_get($permission, 'display_name'),
            'description'     => data_get($permission, 'description'),
        ];
    }
}
