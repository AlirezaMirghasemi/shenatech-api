<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'error' => 'Validation failed',
                    'messages' => $exception->errors(),
                ], 422);
            }

            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'error' => 'Unauthenticated',
                ], 401);
            }

            if ($exception instanceof AuthorizationException) {
                return response()->json([
                    'error' => 'Unauthorized',
                ], 403);
            }

            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'error' => 'Resource not found',
                ], 404);
            }

            return response()->json([
                'error' => 'Server error',
                'message' => $exception->getMessage(),
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
