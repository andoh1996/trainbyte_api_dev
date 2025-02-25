<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            // Handle validation exceptions
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'error' => [
                        'message' => 'Validation Error',
                        'details' => $exception->errors(),
                    ],
                ], 422);
            }

            // Handle authentication exceptions
            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'error' => [
                        'message' => 'Unauthenticated',
                    ],
                ], 401);
            }

            // Handle model not found exceptions
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'error' => [
                        'message' => 'Resource not found',
                    ],
                ], 404);
            }

            // Handle query exceptions
            if ($exception instanceof QueryException) {
                return response()->json([
                    'error' => [
                        'message' => 'Database Query Error',
                    ],
                ], 500);
            }

            // Handle route not found exceptions
            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'error' => [
                        'message' => 'Route not found',
                    ],
                ], 404);
            }

      
            if ($exception instanceof AuthorizationException) {
                return response()->json([
                    'error' => [
                        'message' => 'This action is unauthorized.',
                        'status' => 403,
                    ]
                ], 403);
            }


            // Handle method not allowed exceptions
            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'error' => [
                        'message' => 'Method not allowed',
                    ],
                ], 405);
            }

            // Handle generic HTTP exceptions
            if ($exception instanceof HttpException) {
                return response()->json([
                    'error' => [
                        'message' => $exception->getMessage(),
                        'status' => $exception->getStatusCode(),
                    ],
                ], $exception->getStatusCode());
            }

            // Fallback for other exceptions
            return response()->json([
                'error' => [
                    'message' => $exception->getMessage(),
                    'data' => $exception,
                    'status' => 500,
                ],
            ], 500);
        }

        // Default HTML response for non-JSON requests
        return parent::render($request, $exception);
    }
}


