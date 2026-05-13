<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaintenanceController;

Route::middleware([
    'check.token'
])

->prefix('maintenance')

->group(function () {

    Route::post(
        '/create',
        [MaintenanceController::class, 'create']
    );

    Route::get(
        '/read',
        [MaintenanceController::class, 'read']
    );

    Route::get(
        '/{id}',
        [MaintenanceController::class, 'show']
    );

    Route::put(
        '/{id}/update',
        [MaintenanceController::class, 'update']
    );

    Route::delete(
        '/{id}/delete',
        [MaintenanceController::class, 'delete']
    );
});