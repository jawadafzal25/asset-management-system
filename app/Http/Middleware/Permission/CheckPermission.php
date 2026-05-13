<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckPermission Middleware
 *
 * Usage on routes:
 *   ->middleware('permission:employee.create')
 *   ->middleware('permission:asset.view')
 *
 * Main admin (role_id = null) bypasses every permission check automatically.
 * Regular users must have the permission in their assigned role.
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permissionName): Response
    {
        $user = $request->user();

        return match (true) {
            $user === null                        => response()->unauthorized('Unauthenticated.'),
            $user->isMainAdmin()                  => $next($request),
            $user->hasPermission($permissionName) => $next($request),
            default                               => response()->forbidden(
                "Access denied. Required permission: {$permissionName}."
            ),
        };
    }
}
