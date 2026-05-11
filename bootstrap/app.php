<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withKernels()
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: [
            __DIR__.'/../routes/assignment.php',
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        $middleware->alias([
            'assign.permission' => \App\Http\Middleware\Assignment\CheckAssignmentPermissionMiddleware::class,
            'validate.entities' => \App\Http\Middleware\Assignment\ValidateEntitiesMiddleware::class,
            'check.stock'       => \App\Http\Middleware\Assignment\CheckAssetStockMiddleware::class,
            'verify.active'     => \App\Http\Middleware\Assignment\VerifyActiveAssignmentMiddleware::class,
        ]);

    })
   ->withExceptions(function (Exceptions $exceptions) {

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

})->create();