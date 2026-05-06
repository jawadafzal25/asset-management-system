<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',

        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
   ->withExceptions(function (Illuminate\Foundation\Configuration\Exceptions $exceptions) {

    $exceptions->render(function (\Throwable $e, $request) {

        $status = 500;
        $message = 'Internal Server Error';

        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $status = 404;
            $message = 'Resource not found';
        }

        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            $status = 401;
            $message = 'Unauthenticated';
        }

        if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
            $status = 403;
            $message = 'Unauthorized access';
        }

        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => config('app.debug') ? $e->getMessage() : null,
        ], $status);

    });

});
