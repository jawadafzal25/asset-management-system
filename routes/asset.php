<?php

use App\Http\Controllers\AssetController;
use App\Http\Middleware\Asset\CheckAssetExists;
use Illuminate\Support\Facades\Route;

/*
|------------------------------------------------------------------
| Asset Module Routes
|------------------------------------------------------------------
| Routes are now available at /api/assets (v1 prefix removed).
*/
Route::prefix('assets')->middleware('check.token')->group(function () {

    // Create Asset
    Route::post('/', [AssetController::class, 'create']);

    // Read All Assets
    Route::get('/', [AssetController::class, 'readAll']);

    // Single-asset routes
    Route::prefix('{id}')->middleware(CheckAssetExists::class)->group(function () {

        // Read Single Asset
        Route::get('/', [AssetController::class, 'read']);

        // Update Asset
        Route::put('/', [AssetController::class, 'update']);

        // Delete Asset
        Route::delete('/', [AssetController::class, 'delete']);
    });
});
