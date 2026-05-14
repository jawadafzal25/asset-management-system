<?php

namespace App\Http\Middleware\Log;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * LogLoginActivity
 *
 * Place AFTER check.credentials and check.active on the login route.
 * By the time this runs, $request->user is already set by CheckCredentialMiddleware.
 *
 * Route usage:
 *   Route::post('login', [AuthController::class, 'login'])
 *       ->middleware([
 *           'check.validation:login_request',
 *           'check.credentials',
 *           'check.active',
 *           'log.login',          ← add this
 *       ]);
 */
class LogLoginActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = data_get($request, 'user');

        if ($user) {
            ActivityLogService::logLogin($user);
        }

        return $next($request);
    }
}
