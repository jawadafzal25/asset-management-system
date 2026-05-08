<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::prefix('employees')->group(function () {
    // Single route for both "Read All" and "Read Single"
    // The middleware still runs to protect the ID if it's provided
    Route::get('/read/{id?}', [EmployeeController::class, 'read'])->middleware('check.employee');
    Route::post('/create', [EmployeeController::class, 'create']);
    Route::middleware('check.employee')->group(function () {
        Route::put('/update/{id}', [EmployeeController::class, 'update']);
        Route::delete('/delete/{id}', [EmployeeController::class, 'delete']);
    });
});

