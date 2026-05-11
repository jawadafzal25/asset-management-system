<?php

use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('departments')->group(function () {

    
    Route::get('/read/{id?}', [DepartmentController::class, 'read'])->middleware(['check.token', 'check.dept']);

    // Create Route
    Route::post('/create', [DepartmentController::class, 'create'])->middleware('check.token');

    // ID-Strict Group
    Route::middleware(['check.token', 'check.dept'])->group(function () {
        Route::put('/update/{id}', [DepartmentController::class, 'update']);
        Route::delete('/delete/{id}', [DepartmentController::class, 'delete']);
    });
});
