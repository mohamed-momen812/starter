<?php

namespace App\Traits;

trait ApiTrait
{
    public function responseJsonSuccess($data=null, $message='Successfully Done', $status=200)
    {
        return response()->json([
            "success" => true,
            "status" => $status,
            "message" => $message ,
            "data" => $data
        ], $status);
    }

    public function responseJsonFailed($message="Fail, try again", $status=400 )
    {
        return response()->json([
            "success" => false,
            "status" => $status,
            "message" => $message,
        ], $status);
    }

}
