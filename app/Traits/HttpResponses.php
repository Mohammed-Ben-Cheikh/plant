<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HttpResponses
{
    /**
     * Send a success response.
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function success(mixed $data = null, ?string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Send an error response.
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function error(mixed $data = null, ?string $message = null, int $code = 400): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message ?? 'An error occurred',
            'data' => $data
        ], $code);
    }

    /**
     * Send a validation error response.
     *
     * @param mixed $errors
     * @param string|null $message
     * @return JsonResponse
     */
    protected function validationError(mixed $errors, ?string $message = null): JsonResponse
    {
        return $this->error(
            data: $errors,
            message: $message ?? 'Validation failed',
            code: 422
        );
    }
}