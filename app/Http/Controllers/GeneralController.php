<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class GeneralController extends Controller {

    /**
     * Redirect the visitor to the web client
     *
     * @return JsonResponse
     */
    public function root()
    {
        return response()->json([
            'documentation_url' => 'https://docs.broodt.nu'
        ], 200);
    }

    /**
     * Check if the server is online
     *
     * @return JsonResponse
     */
    public function ping()
    {
        return response()->json([
            'time' => date('Y-m-d H:i:s'),
            'message' => 'pong'
        ], 200);
    }

    /**
     * Return required params for clients
     *
     * @return JsonResponse
     */
    public function meta()
    {
        return response()->json([
            'captcha_key' => config('captcha.public_key')
        ], 200);
    }
}