<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\PermissionByModuleRequest;
use App\Http\Requests\Permission\PermissionIndexRequest;
use App\Http\Resources\Permission\PermissionGroupedResource;
use App\Http\Resources\Permission\PermissionResource;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * GET /api/permissions/grouped
     *
     * Returns all permissions grouped by module.
     * Frontend renders one checkbox card per module from this response.
     */
    public function grouped(): JsonResponse
    {
        $grouped = $this->permissionService->allGroupedByModule();

        return response()->success(
            new PermissionGroupedResource($grouped),
            'Permissions grouped by module retrieved successfully.'
        );
    }

    /**
     * GET /api/permissions
     * GET /api/permissions?per_page=50
     *
     * Paginated flat list of all permissions.
     * data_get() reads per_page from the validated request data.
     */
    public function read(PermissionIndexRequest $request): JsonResponse
    {
        // data_get() pulls 'per_page' from validated request data
        // second argument is the dot-path key
        // third argument is the default value if key is missing
        $perPage = data_get($request->validated(), 'per_page', 20);

        $permissions = $this->permissionService->paginated($perPage);

        return response()->success(
            PermissionResource::collection($permissions),
            'Permissions retrieved successfully.'
        );
    }

    /**
     * GET /api/permissions/by-module?module=Employee Management
     *
     * Returns permissions filtered to one module.
     * data_get() reads 'module' from the validated request data.
     */
    public function byModule(PermissionByModuleRequest $request): JsonResponse
    {
        // data_get() pulls 'module' from the validated request array
        $module = data_get($request->validated(), 'module');

        $permissions = $this->permissionService->byModule($module);

        return response()->success(
            PermissionResource::collection($permissions),
            'Permissions for module retrieved successfully.'
        );
    }

    /**
     * GET /api/permissions/modules
     *
     * Returns all distinct module names for the frontend dropdown.
     */
    public function modules(): JsonResponse
    {
        $modules = $this->permissionService->allModules();

        return response()->success(
            $modules,
            'Available modules retrieved successfully.'
        );
    }

    /**
     * GET /api/permissions/{id}
     *
     * FetchPermission middleware already fetched the permission and
     * set it on $request->attributes as 'permission_data'.
     *
     * We use data_get() to read it — exactly like your teammate:
     *   $employee = data_get($request->attributes, 'employee_data');
     *
     * The service is NOT called here. No DB query happens in the controller.
     */
    public function show(Request $request, $id = null): JsonResponse
    {
        // Read permission from request attributes — set by FetchPermission middleware
        $permission = data_get($request->attributes, 'permission_data');

        return response()->success(
            new PermissionResource($permission),
            'Permission retrieved successfully.'
        );
    }
}
