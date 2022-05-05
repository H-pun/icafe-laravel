<?php

namespace App\Helpers;

class ApiResponse
{
    public static function build($code, $message, $data = null)
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], $code)->header('Content-Type', 'application/json');
    }
}
