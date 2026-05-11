<?php

namespace App\Http\Resources\Permission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * PermissionGroupedResource
 *
 * Returns permissions already grouped by module so the frontend
 * can directly render each module as a card with checkboxes.
 *
 * Response shape:
 * {
 *   "Employee Management": [
 *     { "permission_id": 1, "permission_name": "employee.view",   "display_name": "View Employees",   "action": "view" },
 *     { "permission_id": 2, "permission_name": "employee.create", "display_name": "Create Employee",  "action": "create" }
 *   ],
 *   "Asset Management": [ ... ]
 * }
 */
class PermissionGroupedResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /*
         * $this->resource is a Collection keyed by module name.
         * Each value is a Collection of Permission models for that module.
         *
         * Instead of nested fn() => arrow functions,
         * we use a named method formatPermission() and data_get()
         * to pull each field safely.
         */

        $result = [];

        foreach ($this->resource as $moduleName => $permissions) {
            $result[$moduleName] = $this->formatPermissions($permissions);
        }

        return $result;
    }

    /**
     * Format a group of permissions into a plain array.
     * Uses data_get() on each permission object.
     */
    private function formatPermissions($permissions): array
    {
        $formatted = [];

        foreach ($permissions as $permission) {
            $formatted[] = $this->formatSinglePermission($permission);
        }

        return $formatted;
    }

    /**
     * Format a single Permission model using data_get().
     *
     * data_get($permission, 'permission_id')
     *   → same as $permission->permission_id
     *   → but works on both objects and arrays
     *   → returns null safely if the key doesn't exist
     */
    private function formatSinglePermission($permission): array
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
