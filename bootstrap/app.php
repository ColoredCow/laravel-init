<?php

use App\Http\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(StartSession::class);
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => EnsureEmailIsVerified::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function(ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found'
            ], 404);
        });
        $exceptions->render(function(NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found'
            ], 404);
        });
        $exceptions->render(function(AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action'
            ], 403);
        });

        $exceptions->render(function(ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        });
        $exceptions->render(function(MethodNotAllowedHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Method is not allowed for the requested route'
            ], 405);
        });
        $exceptions->render(function(AccessDeniedHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action'
            ], 403);
        });
        $exceptions->render(function(\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        });

    })->create();
