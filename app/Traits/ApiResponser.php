<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{
    public function successResponse($data, $code = Response::HTTP_OK) //Build success response
    {
        return response()->json(['data' => $data, 'site' => 2], $code);
    }
    public function errorResponse($message, $code) //Build error responses
    {
        return response()->json(['error' => $message, 'site' => 2, 'code' => $code], $code);
    }
}