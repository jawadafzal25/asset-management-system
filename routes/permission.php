<?php

use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Permission Module — API Routes
| Developer: Zain Ul Abideen
|--------------------------------------------------------------------------
|
| Middleware aliases (registered in bootstrap/app.php):
|
|   auth:sanctum      → Sanctum token authentication
|   auth.api          → AuthenticatedMiddleware   (logged in + active check)
|   permission:key    → CheckPermission           (role-based permission gate)
|   fetch.permission  → FetchPermission           (fetches permission by {id},
|                                                  sets on $request->attributes,
|                                                  returns 404 if not found)
|
*/

Route::middleware(['auth:sanctum', 'auth.api', 'permission:permission.view'])
    ->prefix('permissions')
    ->group(function () {

        // GET /api/permissions/grouped
        // FetchPermission middleware NOT needed — no {id} in this route
        Route::get('grouped',   [PermissionController::class, 'grouped']);

        // GET /api/permissions/modules
        // FetchPermission middleware NOT needed — no {id} in this route
        Route::get('modules',   [PermissionController::class, 'modules']);

        // GET /api/permissions/by-module?module=Employee Management
        // FetchPermission middleware NOT needed — filters by query param, not {id}
        Route::get('by-module', [PermissionController::class, 'byModule']);

        // GET /api/permissions
        // FetchPermission middleware NOT needed — returns full list
        Route::get('/',         [PermissionController::class, 'read']);

        // GET /api/permissions/{id}
        // fetch.permission middleware runs FIRST:
        //   → reads {id} from route
        //   → fetches Permission from DB
        //   → if not found → 404 returned, controller never runs
        //   → if found → sets on $request->attributes as 'permission_data'
        // Controller then reads: data_get($request->attributes, 'permission_data')
        Route::get('{id}', [PermissionController::class, 'show'])
            ->middleware('fetch.permission');
    });
