<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        // Register renderable callbacks to ensure consistent JSON responses
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $model = class_basename($e->getModel());
                $humanModel = preg_replace('/(?<!^)([A-Z])/', ' $1', $model);
                $ids = method_exists($e, 'getIds') ? $e->getIds() : null;

                if (!empty($ids)) {
                    $idsStr = is_array($ids) ? implode(', ', $ids) : $ids;
                    $message = "{$humanModel} not found with id(s): {$idsStr}";
                } else {
                    $message = "{$humanModel} not found";
                }

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'model' => $model,
                    'ids' => $ids,
                ], 404);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'API endpoint not found',
                ], 404);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        // For API requests, return a consistent JSON response for common not-found cases
        if ($request->expectsJson() || $request->is('api/*')) {
            if ($e instanceof ModelNotFoundException) {
                $model = class_basename($e->getModel());
                $ids = method_exists($e, 'getIds') ? $e->getIds() : null;

                // Convert CamelCase model name to a human-friendly name (e.g. EmployeeContract -> Employee Contract)
                $humanModel = preg_replace('/(?<!^)([A-Z])/', ' $1', $model);

                if (!empty($ids)) {
                    $idsStr = is_array($ids) ? implode(', ', $ids) : $ids;
                    $message = "{$humanModel} not found with id(s): {$idsStr}";
                } else {
                    $message = "{$humanModel} not found";
                }

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'model' => $model,
                    'ids' => $ids,
                ], 404);
            }

            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'success' => false,
                    'message' => 'API endpoint not found',
                ], 404);
            }
        }

        return parent::render($request, $e);
    }
}
