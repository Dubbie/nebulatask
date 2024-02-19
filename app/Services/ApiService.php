<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class ApiService
{
    /**
     * Generate a success JSON response.
     *
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data = null)
    {
        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * Generate an error JSON response.
     *
     * @param \Exception $exception
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse(Exception $exception, $statusCode = 400)
    {
        Log::error($exception->getMessage());
        return response()->json(['success' => false, 'message' => $exception->getMessage()], $statusCode);
    }
}
