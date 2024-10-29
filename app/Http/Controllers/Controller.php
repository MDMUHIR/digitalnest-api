<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function success($message = null, $data = []) {
        return response()->json([

            'status'    => true,
            'message'   => $message,
            'data'      => $data

        ]);
    }

    public function error($message = null, $data = []) {
        return response()->json([

            'status'    => false,
            'message'   => $message,
            'data'      => $data

        ]);
    }
}
