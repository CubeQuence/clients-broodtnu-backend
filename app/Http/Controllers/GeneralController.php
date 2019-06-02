<?php

namespace App\Http\Controllers;

use App\Helpers\HttpStatusCodes;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

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
                'meta (GET)' => config('app.url') . '/meta',
                'auth (POST)' => [
                    'register' => config('app.url') . '/auth/register',
                    'login' => config('app.url') . '/auth/login',
                    'logout' => config('app.url') . '/auth/logout',
                    'refresh' => config('app.url') . '/auth/refresh',
                    'verify' => config('app.url') . '/auth/verify',
                    'reset_request' => config('app.url') . '/auth/reset/request',
                    'reset' => config('app.url') . '/auth/reset',
                    'all sessions (GET)' => config('app.url') . '/auth/sessions',
                    'revoke specific session (DELETE)' => config('app.url') . '/auth/sessions/{session_uuid}',
                ],
                'user' => [
                    'profile (GET)' => config('app.url') . '/auth/user',
                    'update (PUT)' => config('app.url') . '/auth/user',
                    'delete (DELETE)' => config('app.url') . '/auth/user',
                ],
                'products (GET)' => [
                    'all' => config('app.url') . '/products',
                    'single' => config('app.url') . '/products/1',
                    'multiple' => config('app.url') . '/products/1,2,3',
                    'create (POST)' => config('app.url') . '/products',
                    'update (PUT)' => config('app.url') . '/products/1',
                    'delete (DELETE)' => config('app.url') . '/products/1',
                ],
                'tags (GET)' => [
                    'all' => config('app.url') . '/tags',
                    'single' => config('app.url') . '/tags/1',
                    'multiple' => config('app.url') . '/tags/1,2',
                    'products' => config('app.url') . '/tags/1,2/products',
                    'create (POST)' => config('app.url') . '/tags',
                    'update (PUT)' => config('app.url') . '/tags/1',
                    'delete (DELETE)' => config('app.url') . '/tags/1',
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
                'time' => Carbon::now()->toDateTimeString(),
                'captcha_public_key' => config('captcha.public_key'),
                'jwt_public_key' => config('JWT.public_key')
            ],
            HttpStatusCodes::SUCCESS_OK
        );
    }
}