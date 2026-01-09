<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ApiResponses
{
    public function errorResponse($errors = [] , string $message = '',   int $statusCode =404)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors ,
            'data' => [],
        ], $statusCode);
    }


    public function successResponse($data = [], string $message = "Success", int $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message ,
            'errors' => [],
            'data' => $data,
        ], $statusCode);
    }


}
