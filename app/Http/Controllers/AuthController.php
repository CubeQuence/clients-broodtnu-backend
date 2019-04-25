<?php


namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Helper\JWTHelper;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
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
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        $user = User::select('id', 'password')->where('email', $request->get('email'))->first();

        if (!$user || !Hash::check($request->get('password'), $user->password)) {
            return $this->respond([
                'errors' => [
                    'email or password' => ['is invalid'],
                ]
            ], 422);
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
        $this->validate($request, [
            'refresh_token' => 'required'
        ]);

        // Return a new access_token and refresh_token
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
        $this->validate($request, [
            'refresh_token' => 'required'
        ]);

        return response()->json([
            'success' => JWTHelper::logout($request->get('refresh_token'))
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required|alpha'
        ]);

        // TODO: add captcha

        $user = User::create($request->all());

        return response()->json($user, 201);
    }
}