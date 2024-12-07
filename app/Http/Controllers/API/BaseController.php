<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function sendResponse($response, $status="success", $code=200  ){
        return response()->json([
            'success' => true,
            'status' => $status,
            'data' => $response,
        ], $code);
    }
}
