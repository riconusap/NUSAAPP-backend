<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * Success response
     */
    protected function success($data = null, string $message = 'Success', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Error response
     */
    protected function error(string $message = 'Error', int $code = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Validation error response
     */
    protected function validationError($errors, string $message = 'Validation failed')
    {
        return $this->error($message, 422, $errors);
    }

    /**
     * Not found response
     */
    protected function notFound(string $message = 'Resource not found')
    {
        return $this->error($message, 404);
    }

    /**
     * Unauthorized response
     */
    protected function unauthorized(string $message = 'Unauthorized')
    {
        return $this->error($message, 401);
    }

    /**
     * Forbidden response
     */
    protected function forbidden(string $message = 'Forbidden')
    {
        return $this->error($message, 403);
    }
}
