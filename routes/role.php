<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Role Management Routes
| Base URL: /api/role/*
|--------------------------------------------------------------------------
*/

Route::prefix('role')->group(function () {

    // POST /api/role/create
    Route::post('create', [RoleController::class, 'create'])
        ->middleware([
            'check.validation:create_role_request',
        ]);

    // GET /api/role/read
    Route::get('read', [RoleController::class, 'read']);

    // GET /api/role/read/{id}
    Route::get('read/{id}', [RoleController::class, 'show']);

    // PUT/PATCH /api/role/update/{id}
    Route::put('update/{id}', [RoleController::class, 'update'])
        ->middleware([
            'check.validation:update_role_request',
        ]);

    Route::patch('update/{id}', [RoleController::class, 'update'])
        ->middleware([
            'check.validation:update_role_request',
        ]);

    // DELETE /api/role/delete/{id}
    Route::delete('delete/{id}', [RoleController::class, 'delete']);

});
