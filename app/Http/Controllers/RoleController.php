<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\RoleCreateRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Models\Permission\Permission;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    /**
     * POST /api/role/create
     *
     * Create a new role with permissions.
     */
    public function create(RoleCreateRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Create role data
        $roleData = [
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? strtolower(str_replace(' ', '-', $validated['name'])),
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ];

        $role = Role::create($roleData);

        // Attach permissions if provided (support both 'permissions' and 'permission')
        $permissionIds = $validated['permissions'] ?? $validated['permission'] ?? [];
        if (!empty($permissionIds)) {
            $role->permissions()->attach(array_unique($permissionIds));
        }

        // Always load permissions for response
        $role->load('permissions');

        return response()->success(
            new RoleResource($role),
            'Role created successfully.'
        );
    }

    /**
     * GET /api/role/read
     *
     * List all roles with their permissions.
     */
    public function read(): JsonResponse
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
    public function update(RoleUpdateRequest $request, $id): JsonResponse
    {
        $role = Role::findOrFail($id);

        $validated = $request->validated();

        // Update role data
        $roleData = [
            'name' => $validated['name'] ?? $role->name,
            'slug' => $validated['slug'] ?? ($validated['name'] ? strtolower(str_replace(' ', '-', $validated['name'])) : $role->slug),
            'description' => $validated['description'] ?? $role->description,
            'is_active' => $validated['is_active'] ?? $role->is_active,
        ];

        $role->update($roleData);

        // Update permissions if provided (support both 'permissions' and 'permission')
        $permissionIds = $validated['permissions'] ?? $validated['permission'] ?? null;
        if ($permissionIds !== null) {
            $role->permissions()->sync(array_unique($permissionIds));
        }

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
    public function show($id): JsonResponse
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
    public function delete($id): JsonResponse
    {
        $role = Role::findOrFail($id);
        
        $role->delete();

        return response()->success(
            null,
            'Role deleted successfully.'
        );
    }
}