<?php

namespace App\Http\Middleware\Log;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * LogLogoutActivity
 *
 * Place AFTER check.token on the logout route.
 * By the time this runs, $request->user is set by CheckTokenMiddleware.
 *
 * Route usage:
 *   Route::post('logout', [AuthController::class, 'logout'])
 *       ->middleware([
 *           'check.token',
 *           'log.logout',    ← add this
 *       ]);
 */
class LogLogoutActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = data_get($request, 'user');

        if ($user) {
            ActivityLogService::logLogout($user);
        }

        return $next($request);
    }
}
