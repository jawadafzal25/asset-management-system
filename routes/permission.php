<?php

use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Permission Module — API Routes
|--------------------------------------------------------------------------
|
| All permission routes require custom token authentication
|
*/

Route::middleware(['check.token','check.active'])
    ->prefix('permissions')
    ->group(function () {

        // GET /api/permissions
        // Returns paginated list of all permissions
        Route::get('/', [PermissionController::class, 'read']);

        // GET /api/permissions/grouped
        // Returns permissions grouped by module for frontend UI
        Route::get('grouped', [PermissionController::class, 'grouped']);

        // GET /api/permissions/modules
        // Returns all distinct module names
        Route::get('modules', [PermissionController::class, 'modules']);

        // GET /api/permissions/by-module?module=Employee Management
        // Returns permissions filtered by specific module
        Route::get('by-module', [PermissionController::class, 'byModule']);

        // GET /api/permissions/{id}
        // Returns single permission by ID
        Route::get('{id}', [PermissionController::class, 'detail']);
    });
