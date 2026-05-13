<?php

namespace App\Http\Middleware\Role;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRolePermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get permissions from either 'permissions' or 'permission' key
        $permissions = data_get($request, 'permissions') ?? data_get($request, 'permission');

        // Normalize to an array of unique IDs if provided
        if ($permissions !== null) {
            $permissions = array_unique((array) $permissions);
            $request->merge(['permission_ids' => $permissions]);
        }

        return $next($request);
    }
}
