<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserRoleController extends Controller
{
    /**
     * POST /api/user-role/assign
     * Assign a role to a user.
     */
    public function assign(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user_id = data_get($validated, 'user_id');
        $role_id = data_get($validated, 'role_id');

        $user = User::findOrFail($user_id);
        $user->update(['role_id' => $role_id]);

        return response()->success(
            new UserResource($user->load('assignedRole')),
            'Role assigned successfully.'
        );
    }

    /**
     * GET /api/user-role/read/{user_id}
     * Read a user's assigned role.
     */
    public function read($user_id)
    {
        $user = User::with('assignedRole')->findOrFail($user_id);

        return response()->success(
            new UserResource($user),
            'User role retrieved successfully.'
        );
    }

    /**
     * PUT /api/user-role/update/{user_id}
     * Update a user's assigned role.
     */
    public function update(Request $request, $user_id)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role_id = data_get($validated, 'role_id');

        $user = User::findOrFail($user_id);
        $user->update(['role_id' => $role_id]);

        return response()->success(
            new UserResource($user->load('assignedRole')),
            'User role updated successfully.'
        );
    }

    /**
     * DELETE /api/user-role/delete/{user_id}
     * Remove the assigned role from a user.
     */
    public function delete($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->update(['role_id' => null]);

        return response()->success(
            new UserResource($user),
            'User role removed successfully.'
        );
    }
}
