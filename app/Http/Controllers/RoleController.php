<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\RoleCreateRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * POST /api/role/create
     *
     * Create a new role with permissions.
     */
    public function create(RoleCreateRequest $request)
    {
        $validated = $request->validated();

        // Create role using helper to avoid duplication
        $role = Role::create($this->prepareRoleData($validated));

        // Attach permissions (normalized by CheckRolePermissionMiddleware)
        $role->permissions()->attach(data_get($request, 'permission_ids', []));

        return response()->success(
            new RoleResource($role->load('permissions')),
            'Role created successfully.'
        );
    }

    /**
     * GET /api/role/read
     *
     * List all roles with their permissions.
     */
    public function read()
    {
        $roles = Role::with('permissions')->get();

        return response()->success(
            RoleResource::collection($roles),
            'Roles retrieved successfully.'
        );
    }

    /**
     * PUT /api/role/update/{id}
     *
     * Update an existing role with permissions.
     */
    public function update(RoleUpdateRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $validated = $request->validated();

        // Update role using helper to avoid duplication
        $role->update($this->prepareRoleData($validated, $role));

        // Sync permissions (normalized by CheckRolePermissionMiddleware)
        $role->permissions()->sync(data_get($request, 'permission_ids', $role->permissions->pluck('permission_id')->toArray()));

        return response()->success(
            new RoleResource($role->load('permissions')),
            'Role updated successfully.'
        );
    }

    /**
     * GET /api/role/{id}
     *
     * Get a single role by ID.
     */
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return response()->success(
            new RoleResource($role),
            'Role retrieved successfully.'
        );
    }

    /**
     * DELETE /api/role/delete/{id}
     *
     * Delete an existing role.
     */
    public function delete($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->success(
            null,
            'Role deleted successfully.'
        );
    }

    /**
     * Prepare role data for create/update.
     * Removes duplication and uses data_get for safety.
     */
    private function prepareRoleData(array $validated, $role = null): array
    {
        return [
            'name'        => data_get($validated, 'name', data_get($role, 'name')),
            'slug'        => data_get($validated, 'slug') ?? (data_get($validated, 'name') ? strtolower(str_replace(' ', '-', data_get($validated, 'name'))) : data_get($role, 'slug')),
            'description' => data_get($validated, 'description', data_get($role, 'description')),
            'is_active'   => data_get($validated, 'is_active', data_get($role, 'is_active', true)),
        ];
    }
}