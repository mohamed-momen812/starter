<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

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

    public function dataPaginate($data)
    {
        $page = request()->page ?? 1;

        $perPage = request()->perPage ?? 10;
        if($perPage < 0){
            return ['data' => $data];
        }

        $paginatedData = new LengthAwarePaginator(
            $data->forPage($page, $perPage)->values(),
            $data->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return $paginatedData;
    }

}
