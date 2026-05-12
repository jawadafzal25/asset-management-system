<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        return match (true) {
            $user === null    => response()->unauthorized('Unauthenticated.'),
            !$user->is_active => response()->forbidden('Your account has been deactivated.'),
            default           => $next($request),
        };
    }
}
