<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
   

if (!function_exists('jsonError')) {
    function jsonError(string $message, array $errors = [], int $status = 500) {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
       // Validation error
    $exceptions->render(function (Illuminate\Validation\ValidationException  $e, $request) {
        return jsonError(
            message: 'Validation error',
            errors: $e->errors(),
            status: 422
        );
    });

    // Model not found
    $exceptions->render(function (Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
        return jsonError(
            message: 'Resource not found',
            errors: [],
            status: 404
        );
    });
    // NotFoundHttpException
    $exceptions->render(function (Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
    
    if ($e->getPrevious() instanceof Illuminate\Database\Eloquent\ModelNotFoundException) {
        return jsonError('Resource not found', [], 404);
    }
    return jsonError('Route not found', [], 404);
});

    // Authentication exception
    $exceptions->render(function (Illuminate\Auth\AuthenticationException $e, $request) {
        return jsonError(
            message: 'Unauthenticated',
            errors: [],
            status: 401
        );
    });

   $exceptions->render(function (Throwable $e, $request) {

    // Logging 
    \Illuminate\Support\Facades\Log::error($e->getMessage(), [
        'exception' => $e,
        'user_id'   => optional($request->user())->id,
        'url'       => $request->fullUrl(),
        'method'    => $request->method(),
        'ip'        => $request->ip(),
    ]);

    
    if (config('app.debug')) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'errors' => [],
            'trace' => $e->getTrace()
        ], 500);
    }

    return jsonError('Internal server error', [], 500);
    });

    })->create();

