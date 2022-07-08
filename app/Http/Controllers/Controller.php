<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function send_response(string $message = 'Success', int $status_code = 200, array $errors = [], $data = [])
    {
    	$payload = [
            'message' => $message,
        ];

        if(!empty($errors)){
            $payload['errors'] = $errors;
        }
        elseif(!empty($data)){
            $payload['data'] = $data;
        }

        return response()->json($payload, $status_code);
    }
}
