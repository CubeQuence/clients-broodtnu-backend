<?php

namespace App\Http\Controllers;

use App\Helpers\HttpStatusCodes;
use Illuminate\Http\JsonResponse;

class GeneralController extends Controller {

    /**
     * Redirect the visitor to the web client
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(
            [
                'documentation_url (GET)' => 'https://docs.broodt.nu',
                'meta (GET)' => env('APP_URL') . '/meta',
                'auth (POST)' => [
                    'register' => env('APP_URL') . '/auth/register',
                    'login' => env('APP_URL') . '/auth/login',
                    'logout' => env('APP_URL') . '/auth/logout',
                    'refresh' => env('APP_URL') . '/auth/refresh',
                    'verify' => env('APP_URL') . '/auth/verify',
                    'reset_request' => env('APP_URL') . '/auth/reset/request',
                    'reset' => env('APP_URL') . '/auth/reset',
                ],
                'user' => [
                    'profile (GET)' => env('APP_URL') . '/auth/user',
                    'update (PUT)' => env('APP_URL') . '/auth/user',
                    'delete (DELETE)' => env('APP_URL') . '/auth/user',
                ],
                'products (GET)' => [
                    'all' => env('APP_URL') . '/products',
                    'single' => env('APP_URL') . '/products/1',
                    'multiple' => env('APP_URL') . '/products/1,2,3',
                ],
                'tags (GET)' => [
                    'all' => env('APP_URL') . '/tags',
                    'single' => env('APP_URL') . '/tags/1',
                    'multiple' => env('APP_URL') . '/tags/1,2',
                    'products' => env('APP_URL') . '/tags/1,2/products',
                ]
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
                'time' => date('Y-m-d H:i:s'),
                'captcha_public_key' => config('captcha.public_key'),
                'jwt_public_key' => config('JWT.public_key')
            ],
            HttpStatusCodes::SUCCESS_OK
        );
    }
}