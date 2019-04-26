<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Helpers\JWTHelper;
use App\Http\Helpers\CaptchaHelper;
use App\Http\Validators\ValidatesAuthRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    use ValidatesAuthRequests;

    /**
     * Login user and return tokens
     *
     * @param Request $request
     *
     * @return mixed
     * @throws
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        $user = User::where('email', $request->get('email'))->first();

        if (!$user || !Hash::check($request->get('password'), $user->password)) {
            return $this->respond([
                'errors' => [
                    'email or password' => ['is invalid'],
                ]
            ], 401);
        }

        return response()->json(JWTHelper::issue($user->id, $request->ip()), 200);
    }

    /**
     * Refreshes the access_token
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws
     */
    public function refresh(Request $request) {
        $this->validateRefreshToken($request);

        return response()->json(JWTHelper::refresh($request->get('refresh_token'), $request->ip()));
    }

    /**
     * Revoke the refresh_token
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws
     */
    public function logout(Request $request) {
        $this->validateRefreshToken($request);

        return response()->json([
            'success' => (bool) JWTHelper::logout($request->get('refresh_token'))
        ]);
    }

    /**
     * Register account
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws
     */
    public function register(Request $request) {
        $this->validateRegister($request);

        if (!CaptchaHelper::validate($request->get('captcha_response'))) {
            return response()->json([
                'error' => 'invalid captcha'
            ], 401);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->input('password'))
        ]);

        return response()->json($user, 201);
    }
}