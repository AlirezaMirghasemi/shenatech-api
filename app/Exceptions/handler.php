<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request; // Import Request
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse; // Import JsonResponse

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        AuthorizationException::class,
        AuthenticationException::class,
        ModelNotFoundException::class, // Catch specific model not found
        NotFoundHttpException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Optional: Send errors to external service like Sentry
            // if (app()->bound('sentry') && $this->shouldReport($e)) {
            //     app('sentry')->captureException($e);
            // }
        });

        // Customize rendering for API requests
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) { // Check if API request or expects JSON
                return $this->handleApiException($e);
            }
        });
    }

    /**
     * Handle exceptions for API requests.
     *
     * @param Throwable $e
     * @return JsonResponse
     */
    protected function handleApiException(Throwable $e): JsonResponse
    {
        $statusCode = $this->getStatusCode($e);
        $response = [
            'message' => $this->getMessage($e, $statusCode),
        ];

        // Add validation errors if applicable
        if ($e instanceof ValidationException) {
            $response['errors'] = $e->errors();
        }

        // Add debug information if APP_DEBUG is true
        if (config('app.debug')) {
            $response['exception'] = get_class($e);
            $response['file'] = $e->getFile();
            $response['line'] = $e->getLine();
            $response['trace'] = collect($e->getTrace())->map(function ($trace) {
                return \Illuminate\Support\Arr::except($trace, ['args']); // Remove args for brevity/security
            })->all();
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Get the appropriate HTTP status code for the exception.
     *
     * @param Throwable $e
     * @return int
     */
    protected function getStatusCode(Throwable $e): int
    {
        if ($this->isHttpException($e)) {
            // Check if getStatusCode method exists before calling
            if (method_exists($e, 'getStatusCode')) {
                return $e->getStatusCode();
            }
        }

        if ($e instanceof ModelNotFoundException) {
            return 404;
        }

        if ($e instanceof AuthenticationException) {
            return 401;
        }

        if ($e instanceof AuthorizationException) {
            return 403;
        }

        if ($e instanceof ValidationException) {
            return 422;
        }

        // Check for common PDOExceptions or QueryExceptions (optional)
        if ($e instanceof \Illuminate\Database\QueryException) {
            // Log the detailed query exception for debugging
            \Illuminate\Support\Facades\Log::error('Query Exception:', ['error' => $e->getMessage()]);
            // Return a generic 500 error to the client
            return 500;
        }


        // Default to 500 Internal Server Error
        // Use getCode() if it's a valid HTTP status code, otherwise default to 500
        if (method_exists($e, 'getCode') && is_int($e->getCode()) && $e->getCode() >= 100 && $e->getCode() < 600) {
            return $e->getCode();
        }

        return 500;
    }

    /**
     * Get the error message for the exception.
     *
     * @param Throwable $e
     * @param int $statusCode
     * @return string
     */
    protected function getMessage(Throwable $e, int $statusCode): string
    {
        // Use default status text for common HTTP errors unless a specific message exists
        switch ($statusCode) {
            case 401:
                return $e->getMessage() ?: 'Unauthenticated.';
            case 403:
                return $e->getMessage() ?: 'Forbidden.';
            case 404:
                // Handle ModelNotFoundException specifically
                if ($e instanceof ModelNotFoundException) {
                    $modelName = class_basename($e->getModel());
                    return "Resource '{$modelName}' not found.";
                }
                return $e->getMessage() ?: 'Not Found.';
            case 422:
                return $e->getMessage() ?: 'Validation Error.';
            case 500:
                // Avoid exposing sensitive details in production
                return config('app.debug') && !($e instanceof \Illuminate\Database\QueryException) ? $e->getMessage() : 'Server Error.';
            default:
                // Return message only if it's not empty, otherwise a generic message
                return $e->getMessage() ?: "An error occurred (Status Code: {$statusCode}).";
        }
    }
}
