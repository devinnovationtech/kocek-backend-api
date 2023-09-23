<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        });
    }

    public function render($request, Throwable $exception)
    {
        if (!$request->expectsJson()) {
            if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
                return new JsonResponse([
                    'status' => false,
                    'error' => 'Resource not found',
                ], 404);
            }

            return new JsonResponse([
                'status' => false,
                'error' => $exception->getMessage(),
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
