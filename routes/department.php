<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;

Route::prefix('api/departments')->group(function () {

    Route::post('/', [DepartmentController::class, 'create']);
    Route::get('/{id?}', [DepartmentController::class, 'read'])->middleware('check.dept');

    Route::middleware('check.dept')->group(function () {
        Route::put('/{id}', [DepartmentController::class, 'update']);
        Route::delete('/{id}', [DepartmentController::class, 'delete']);
    });
});
