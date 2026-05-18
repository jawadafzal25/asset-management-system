<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssignmentController;
// use App\Http\Middleware\CheckTokenMiddleware; 

Route::prefix('assignments')->middleware('check.token')
    ->group(function () {

        Route::post('/', [AssignmentController::class, 'store']);

        Route::patch('/return', [AssignmentController::class, 'returnAsset']);

        Route::get('/history', [AssignmentController::class, 'history']);
        
    });