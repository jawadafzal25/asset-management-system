<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MainAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        return match (true) {
            $user === null        => response()->unauthorized('Unauthenticated.'),
            !$user->isMainAdmin() => response()->forbidden('Only the main admin can access this resource.'),
            default               => $next($request),
        };
    }
}
