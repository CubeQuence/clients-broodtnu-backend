<?php


namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Helper\JWTHelper;

class AuthController extends Controller {
    /**
     * Authenticate a user
     *
     * @param Request $request
     *
     * @return mixed
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find the user by email
        $user = User::where('email', $request->get('email'))->first();

        // Validate user credentials
        if (!$user || !Hash::check($request->get('password'), $user->password)) {
            return response()->json([
                'error' => 'Email or password is wrong.'
            ], 400);
        }

        // Return tokens for successful auth
        return response()->json(JWTHelper::issue($user->id, $request->ip()), 200);
    }

    /**
     * Refreshes the access_token
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
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
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function logout(Request $request) {
        $this->validate($request, [
            'refresh_token' => 'required'
        ]);

        return response()->json([
            'success' => JWTHelper::logout($request->get('refresh_token'))
        ]);
    }
}