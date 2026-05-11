<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * POST /api/role/create
     * Create a new role
     */
    public function create(CreateRoleRequest $request)
    {
        $role = Role::add($request->validated());

        return response()->success([
            'role' => RoleResource::make($role),
        ], 'Role created successfully!');
    }

    /**
     * GET /api/role/read
     * Get all roles with optional filtering
     */
    public function read(Request $request)
    {
        $query = Role::query();

        // Filter by active status if provided
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Search by name or description
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Include user count
        $roles = $query->withCount('users')->get();

        return response()->success([
            'roles' => RoleResource::collection($roles),
        ], 'Roles retrieved successfully!');
    }

    /**
     * GET /api/role/read/{id}
     * Get a specific role
     */
    public function show($id)
    {
        $role = Role::withCount('users')->findOrFail($id);

        return response()->success([
            'role' => RoleResource::make($role),
        ], 'Role retrieved successfully!');
    }

    /**
     * PUT/PATCH /api/role/update/{id}
     * Update a role
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);

        $data = $request->validated();

        // Check if name is being updated and if it already exists
        if (isset($data['name']) && $data['name'] !== $role->name) {
            $existingRole = Role::where('name', $data['name'])->first();
            if ($existingRole) {
                return response()->error([
                    'message' => 'Role already exists with this name.',
                ], 409);
            }
        }

        // Check if slug is being updated and if it already exists
        if (isset($data['slug']) && $data['slug'] !== $role->slug) {
            $existingSlug = Role::where('slug', $data['slug'])->first();
            if ($existingSlug) {
                return response()->error([
                    'message' => 'Role slug already exists.',
                ], 409);
            }
        }

        $role->update($data);

        return response()->success([
            'role' => RoleResource::make($role->fresh()),
        ], 'Role updated successfully!');
    }

    /**
     * DELETE /api/role/delete/{id}
     * Delete a role
     */
    public function delete($id)
    {
        $role = Role::findOrFail($id);

        // Check if role has users
        if ($role->users()->count() > 0) {
            return response()->error([
                'message' => 'Cannot delete role. It is assigned to users.',
            ], 422);
        }

        $role->delete();

        return response()->success(null, 'Role deleted successfully!');
    }
}
