<?php

namespace App\Http\Middleware\Log;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * LogFailedAction
 *
 * Wraps any route and logs the action if the response status >= 400.
 *
 * Useful for:
 *   - Failed login attempts (wrong credentials)
 *   - Unauthorized access attempts
 *   - Any failed operation you want audited
 *
 * Route usage:
 *   Route::post('login', [AuthController::class, 'login'])
 *       ->middleware([
 *           'log.failed:Auth',   ← module name as parameter
 *           'check.validation:login_request',
 *           'check.credentials',
 *           ...
 *       ]);
 */
class LogFailedAction
{
    public function handle(Request $request, Closure $next, string $module = 'Unknown'): Response
    {
        $response = $next($request);

        // Only log if the response indicates a failure (4xx or 5xx)
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400) {
            // Try to get error message from response body
            $body         = json_decode($response->getContent(), true);
            $errorMessage = data_get($body, 'message', 'Unknown error');

            ActivityLogService::logFailed($module, $errorMessage, [
                'new_values' => [
                    'attempted_email' => $request->input('email'),
                    'status_code'     => $statusCode,
                ],
            ]);
        }

        return $response;
    }
}
