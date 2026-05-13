<?php

use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('user-role')->middleware(['check.token'])->group(function () {
    
    // POST /api/user-role/assign
    Route::post('assign', [UserRoleController::class, 'assign']);
    
    // GET /api/user-role/read/{user_id}
    Route::get('read/{user_id}', [UserRoleController::class, 'read']);
    
    // PUT /api/user-role/update/{user_id}
    Route::put('update/{user_id}', [UserRoleController::class, 'update']);
    
    // DELETE /api/user-role/delete/{user_id}
    Route::delete('delete/{user_id}', [UserRoleController::class, 'delete']);
    
});
