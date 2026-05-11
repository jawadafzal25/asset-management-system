<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::middleware([
    'check.token'
])

->prefix('categories')

->group(function () {

    Route::post(
        '/create',
        [CategoryController::class, 'create']
    );

    Route::get(
        '/read',
        [CategoryController::class, 'read']
    );

    Route::get(
        '/{id}',
        [CategoryController::class, 'show']
    );

    Route::put(
        '/{id}/update',
        [CategoryController::class, 'update']
    );

    Route::delete(
        '/{id}/delete',
        [CategoryController::class, 'delete']
    );
});