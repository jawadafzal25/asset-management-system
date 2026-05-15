<?php

use App\Http\Middleware\AuthenticatedMiddleware;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\FetchPermission;
use App\Http\Middleware\Log\FetchActivityLog;
use App\Http\Middleware\Log\LogFailedAction;
use App\Http\Middleware\Log\LogLoginActivity;
use App\Http\Middleware\Log\LogLogoutActivity;
use App\Http\Middleware\MainAdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))

    ->withProviders([
        \App\Providers\ResponseServiceProvider::class,
    ])

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',

        api: [
            __DIR__ . '/../routes/api.php',
            __DIR__ . '/../routes/auth.php',
            __DIR__ . '/../routes/department.php',
            __DIR__ . '/../routes/employee.php',
            __DIR__ . '/../routes/permission.php',   // Zain — Permission Module
            __DIR__ . '/../routes/role.php',
            __DIR__ . '/../routes/user_role.php',
            __DIR__ . '/../routes/category.php',
            __DIR__ . '/../routes/asset.php',
            __DIR__ . '/../routes/assignment.php',
            __DIR__ . '/../routes/file.php',
            __DIR__ . '/../routes/maintenance.php',
        ],

        apiPrefix: 'api',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([

            /*
            |--------------------------------------------------------------------------
            | Activity Log Middleware  ← NEW
            |--------------------------------------------------------------------------
            */

            'log.login'  => LogLoginActivity::class,
            'log.logout' => LogLogoutActivity::class,
            'log.failed' => LogFailedAction::class,
            'fetch.log'  => FetchActivityLog::class,

            /*
            |--------------------------------------------------------------------------
            | Permission Module Middleware
            |--------------------------------------------------------------------------
            */

            'auth.api'         => AuthenticatedMiddleware::class,
            'main_admin'       => MainAdminMiddleware::class,
            'permission'       => CheckPermission::class,
            'fetch.permission' => FetchPermission::class,

            /*
            |--------------------------------------------------------------------------
            | Auth Middleware
            |--------------------------------------------------------------------------
            */

            'check.user.exists' =>
            \App\Http\Middleware\Auth\CheckUserExistMiddleware::class,

            'check.active' =>
            \App\Http\Middleware\Auth\CheckActiveMiddleware::class,

            'check.credentials' =>
            \App\Http\Middleware\Auth\CheckCredentialMiddleware::class,

            'check.token' =>
            \App\Http\Middleware\Auth\CheckTokenMiddleware::class,

            'check.user.exists.forgot' =>
            \App\Http\Middleware\Auth\CheckUserExistForForgetMiddleware::class,

            'check.validation' =>
            \App\Http\Middleware\CheckValidationMiddleware::class,

            'check.verify.signup' =>
            \App\Http\Middleware\Auth\CheckVerifySignupMiddleware::class,

            'check.profile_picture' =>
            \App\Http\Middleware\Auth\CheckProfileUpdateMiddleware::class,

            /*
            |--------------------------------------------------------------------------
            | Employee Middleware
            |--------------------------------------------------------------------------
            */

            'check.employee' =>
            \App\Http\Middleware\Employee\CheckEmployeeMiddleware::class,

            /*
            |--------------------------------------------------------------------------
            | Department Middleware
            |--------------------------------------------------------------------------
            */

            'check.dept' =>
            \App\Http\Middleware\CheckDepartmentMiddleware::class,

            'check.role_permission' =>
            \App\Http\Middleware\Role\CheckRolePermissionMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->reportable(function (\Throwable $e) {
            \App\Services\WebhookNotifierService::notifyIfServerError($e);
        });

        $exceptions->render(function (\Throwable $e, Request $request) {

            if ($request->is('api/*') || $request->expectsJson()) {

                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->validation($e->errors(), 'Validation failed', 400);
                }

                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->unauthorized('Authentication required', 401);
                }

                if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    return response()->forbidden('Access denied', 403);
                }

                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    return response()->notFound('Resource not found', 404);
                }

                if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                    return response()->error('Method not allowed', 405);
                }

                if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    return response()->notFound('Endpoint not found', 404);
                }

                $status  = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                $message = $e->getMessage() ?: 'Internal server error';

                if (app()->environment('production') && $status === 500) {
                    $message = 'Internal server error';
                }

                return response()->error($message, $status);
            }
        });
    })

    ->create();
