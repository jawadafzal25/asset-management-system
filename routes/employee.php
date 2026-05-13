<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::prefix('employees')->group(function () {
    
    // Read all employees
    Route::get('/read', [EmployeeController::class, 'read'])->middleware('check.token');
    
    // Read single employee by ID
    Route::get('/detail/{id}', [EmployeeController::class, 'detail'])->middleware(['check.token', 'check.employee']);

    // Create employee
    Route::post('/create', [EmployeeController::class, 'create'])->middleware('check.token');

    // Update employee
    Route::put('/update/{id}', [EmployeeController::class, 'update'])->middleware(['check.token', 'check.employee']);
    
    // Delete employee
    Route::delete('/delete/{id}', [EmployeeController::class, 'delete'])->middleware(['check.token', 'check.employee']);
});

