<?php

use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('departments')->group(function () {

    // Unified Read Route (Handles /read and /read/5)
    Route::get('/read/{id?}', [DepartmentController::class, 'read'])->middleware('check.dept');

    // Create Route
    Route::post('/create', [DepartmentController::class, 'create']);

    // ID-Strict Group
    Route::middleware('check.dept')->group(function () {
        Route::put('/update/{id}', [DepartmentController::class, 'update']);
        Route::delete('/delete/{id}', [DepartmentController::class, 'delete']);
    });
});
