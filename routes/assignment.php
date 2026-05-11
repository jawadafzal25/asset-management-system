<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssignmentController;
 //use App\Http\Middleware\CheckTokenMiddleware; 

Route::middleware([
     'validate.entities'])
    ->prefix('assignments')
    ->group(function () {
        
        Route::post('/checkout', [AssignmentController::class, 'checkout'])
            ->middleware('check.stock');
        
        Route::put('/checkin', [AssignmentController::class, 'checkin'])
            ->middleware('verify.active');
            
        Route::get('/history/{asset_id?}', [AssignmentController::class, 'history']);

});