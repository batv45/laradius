<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseJsonSuccess(string $message, array $data = [], int $status = 200)
    {
        $respon = [
            'message' => $message
        ];
        return response()->json(array_merge($respon,$data),$status);
    }

    public function responseJsonError(string $message, array $data = [], int $status = 400)
    {
        $respon = [
            'message' => $message
        ];
        return response()->json(array_merge($respon,$data),$status);
    }
}
