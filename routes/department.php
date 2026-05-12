<?php

use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('departments')->group(function () {
    
    // Read all departments
    Route::get('/read', [DepartmentController::class, 'read'])->middleware('check.token');
    
    // Read single department by ID
    Route::get('/detail/{id}', [DepartmentController::class, 'detail'])->middleware(['check.token', 'check.dept']);

    // Create department
    Route::post('/create', [DepartmentController::class, 'create'])->middleware('check.token');

    // Update department
    Route::put('/update/{id}', [DepartmentController::class, 'update'])->middleware(['check.token', 'check.dept']);
    
    // Delete department
    Route::delete('/delete/{id}', [DepartmentController::class, 'delete'])->middleware(['check.token', 'check.dept']);
});
