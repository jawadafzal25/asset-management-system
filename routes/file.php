<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| File Streaming Routes (GridFS)
|--------------------------------------------------------------------------
*/

Route::get('/files/{id}', [FileController::class, 'stream'])->name('files.stream');
