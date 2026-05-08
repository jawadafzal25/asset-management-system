<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::prefix('employees')->group(function () {
    Route::get('/', [EmployeeController::class, 'read']);
    Route::post('/', [EmployeeController::class, 'create']);

    // Group routes that require ID validation
    Route::middleware('check.employee')->group(function () {
        Route::get('/{id}', [EmployeeController::class, 'read']);
        Route::put('/{id}', [EmployeeController::class, 'update']);
        Route::delete('/{id}', [EmployeeController::class, 'delete']);
    });
});
