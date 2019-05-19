<?php

namespace App\Http\Controllers;

use App\Http\Helpers\HttpStatusCodes;
use Illuminate\Http\JsonResponse;

class GeneralController extends Controller {

    /**
     * Redirect the visitor to the web client
     *
     * @return JsonResponse
     */
    public function root()
    {
        return response()->json(
            [
                'documentation_url' => 'https://docs.broodt.nu'
            ],
            HttpStatusCodes::SUCCESS_OK
        );
    }

    /**
     * Check if the server is online
     *
     * @return JsonResponse
     */
    public function ping()
    {
        return response()->json(
            [
                'time' => date('Y-m-d H:i:s'),
                'message' => 'pong'
            ],
            HttpStatusCodes::SUCCESS_OK
        );
    }

    /**
     * Return required params for clients
     *
     * @return JsonResponse
     */
    public function meta()
    {
        return response()->json(
            [
                'captcha_public_key' => config('captcha.public_key'),
                'jwt_public_key' => config('JWT.public_key')
            ],
            HttpStatusCodes::SUCCESS_OK
        );
    }
}