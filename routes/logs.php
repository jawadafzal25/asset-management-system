<?php

use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Activity Log Routes
|--------------------------------------------------------------------------
|
| Only authenticated users with check.token + check.active can view logs.
| In production, restrict further with permission:logs.view
|
*/

Route::prefix('logs')->group(function () {

    // GET /api/logs
    // Paginated list with filters: module, action, status, user_id, from, to
    Route::get('/', [ActivityLogController::class, 'read'])
        ->middleware([
            'check.token',
            'check.active',
        ]);

    // GET /api/logs/{id}
    // Single log entry detail
    Route::get('{id}', [ActivityLogController::class, 'detail'])
        ->middleware([
            'check.token',
            'check.active',
            'fetch.log',
        ]);
});
