<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {

    // POST /api/auth/signup
    Route::post('signup', [AuthController::class, 'signup'])
        ->middleware([
            'check.validation:signup_request',
            'check.user.exists',
        ]);

    // POST /api/auth/verify-signup
    Route::post('verify-signup', [AuthController::class, 'verifySignup'])
        ->middleware([
            'check.validation:verify_signup_request',
            'check.verify.signup',
        ]);

    // POST /api/auth/login
    // log.failed runs first so even rejected logins are captured
    // log.login runs after credentials pass so only successful logins are logged
    Route::post('login', [AuthController::class, 'login'])
        ->middleware([
            'log.failed:Auth',          // ← logs failed login attempts
            'check.validation:login_request',
            'check.credentials',
            'check.active',
            'log.login',                // ← logs successful logins
        ]);

    // POST /api/auth/logout
    Route::post('logout', [AuthController::class, 'logout'])
        ->middleware([
            'check.token',
            'log.logout',               // ← logs logout
        ]);

    // POST /api/auth/forgot-password
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])
        ->middleware([
            'check.validation:forgot_password_request',
            'check.user.exists.forgot',
        ]);

    // POST /api/auth/reset-password
    Route::post('reset-password', [AuthController::class, 'resetPassword'])
        ->middleware([
            'check.validation:reset_password_request',
            'check.token:forgot_password_token',
        ]);

    // PATCH /api/auth/update-password
    Route::patch('update-password', [AuthController::class, 'updatePassword'])
        ->middleware([
            'check.token',
            'check.validation:update_password_request',
        ]);

    // GET /api/auth/read
    Route::get('read', [AuthController::class, 'read'])
        ->middleware([
            'check.token',
        ]);

    // PUT /api/auth/update
    Route::put('update', [AuthController::class, 'update'])
        ->middleware([
            'check.token',
            'check.profile_picture',
        ]);

});
