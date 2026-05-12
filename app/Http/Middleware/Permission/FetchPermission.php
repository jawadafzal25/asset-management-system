<?php

namespace App\Http\Middleware;

use App\Repositories\PermissionRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * FetchPermission Middleware
 *
 * Runs on routes that have a {id} parameter.
 *
 * What it does:
 *   1. Reads {id} from the route
 *   2. Fetches the Permission from the database
 *   3. If not found → returns 404 immediately, controller never runs
 *   4. If found → sets it on $request->attributes as 'permission_data'
 *
 * Controller then reads it with:
 *   data_get($request->attributes, 'permission_data')
 *
 * Same pattern as your teammate's employee module:
 *   $employee = data_get($request->attributes, 'employee_data');
 */
class FetchPermission
{
    protected PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $id         = $request->route('id');
        $permission = $this->permissionRepository->findById($id);

        // Not found — stop here, return 404, controller never runs
        if ($permission === null) {
            return response()->notFound('Permission not found.');
        }

        // Found — attach to request attributes so controller can data_get() it
        $request->attributes->set('permission_data', $permission);

        return $next($request);
    }
}
