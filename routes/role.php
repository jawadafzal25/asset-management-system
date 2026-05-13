<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;


Route::prefix('role')->group(function () {
    
    // GET /api/role/read (requires authentication)
    Route::get('read', [RoleController::class, 'read']);
    
    // PUT /api/role/update/{id} (requires authentication)
    Route::put('update/{id}', [RoleController::class, 'update'])
        ->middleware(['check.token', 'check.role_permission']);
    
    // DELETE /api/role/delete/{id} (requires authentication)
    Route::delete('delete/{id}', [RoleController::class, 'delete']);
    
    // POST /api/role/create (no authentication)
    Route::post('create', [RoleController::class, 'create'])
        ->middleware(['check.token', 'check.role_permission']);
});