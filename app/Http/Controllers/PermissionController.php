<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\PermissionByModuleRequest;
use App\Http\Requests\Permission\PermissionIndexRequest;
use App\Http\Resources\PermissionResource;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * GET /api/permissions
     * Returns flat paginated list of all permissions.
     */
    public function read(PermissionIndexRequest $request): JsonResponse
    {
        $perPage     = data_get($request->validated(), 'per_page', 20);
        $permissions = $this->permissionService->paginated($perPage);

        return response()->success(PermissionResource::collection($permissions));
    }

    /**
     * GET /api/permissions/{id}
     * FetchPermission middleware sets 'permission_data' on request attributes.
     * Controller reads it with data_get() — no DB call here.
     */
    public function detail(Request $request): JsonResponse
    {
        $permission = data_get($request->attributes, 'permission_data');

        return response()->success(new PermissionResource($permission));
    }

    /**
     * GET /api/permissions/grouped
     * Returns all permissions grouped by module for frontend checkbox UI.
     * Uses PermissionResource::grouped() static method.
     */
    public function grouped(): JsonResponse
    {
        $grouped = $this->permissionService->allGroupedByModule();

        return response()->success(PermissionResource::grouped($grouped));
    }

    /**
     * GET /api/permissions/by-module?module=Employee Management
     * Returns permissions filtered to one module only.
     */
    public function byModule(PermissionByModuleRequest $request): JsonResponse
    {
        $module      = data_get($request->validated(), 'module');
        $permissions = $this->permissionService->byModule($module);

        return response()->success(PermissionResource::collection($permissions));
    }

    /**
     * GET /api/permissions/modules
     * Returns all distinct module names for frontend dropdown.
     */
    public function modules(): JsonResponse
    {
        $modules = $this->permissionService->allModules();

        return response()->success($modules);
    }
}
