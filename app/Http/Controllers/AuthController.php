<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\RegisterConfirmation;
use App\Mail\RequestResetPassword;
use App\Http\Helpers\JWTHelper;
use App\Http\Helpers\CaptchaHelper;
use App\Http\Helpers\HttpStatusCodes;
use App\Http\Validators\ValidatesAuthRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
            ], HttpStatusCodes::CLIENT_ERROR_UNAUTHORIZED);
        }

        if ($user->verify_email_token !== null) {
            return $this->respond([
                'errors' => [
                    'account' => ['not active'],
                ]
            ], HttpStatusCodes::CLIENT_ERROR_UNAUTHORIZED);
        }

        return response()->json(
            JWTHelper::issue(
                $user->id,
                $request->ip()
            ),
            HttpStatusCodes::SUCCESS_OK
        );
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

        return response()->json(
            JWTHelper::refresh(
                $request->get('refresh_token'),
                $request->ip()
            ),
            HttpStatusCodes::SUCCESS_OK
        );
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

        return response()->json(
            [
                'success' => (bool) JWTHelper::logout($request->get('refresh_token'))
            ],
            HttpStatusCodes::SUCCESS_OK
        );
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
            return response()->json(
                [
                    'error' => 'invalid captcha'
                ],
                HttpStatusCodes::CLIENT_ERROR_UNAUTHORIZED
            );
        }

        Mail::to($request->get('email'))->send(new RegisterConfirmation());

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->input('password')),
            'verify_email_token' => str_random(128)
        ]);

        return response()->json(
            $user,
            HttpStatusCodes::SUCCESS_CREATED
        );
    }

    /**
     * Request an reset password email
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws
     */
    public function requestResetPassword(Request $request) {
        $this->validateRequestPasswordReset($request);

        $user = User::where(
            'email',
            $request->input('email')
        )->first();

        $user->reset_password_token = str_random(100);

        $user->save();

        Mail::to($request->get('email'))->send(new RequestResetPassword($user));

        return response()->json(
            null,
            HttpStatusCodes::SUCCESS_NO_CONTENT
        );
    }

    /**
     * Confirm a password reset
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws
     */
    public function resetPassword(Request $request) {
        $this->validatePasswordReset($request);

        $user = User::where(
            'reset_password_token',
            $request->input('reset_password_token')
        )->first();

        $user->password = Hash::make($request->input('password'));
        
        $user->reset_password_token = null;

        $user->save();

        return response()->json(
            null,
            HttpStatusCodes::SUCCESS_NO_CONTENT
        );
    }


    /**
     * Verify user email and activate account
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws
     */
    public function verifyEmail(Request $request) {
        $this->validateVerifyEmailToken($request);

        $user = User::where(
            'verify_email_token',
            $request->input('verify_email_token')
        )->first();
        
        $user->verify_email_token = null;
        
        $user->save();

        return response()->json(
            null,
            HttpStatusCodes::SUCCESS_NO_CONTENT
        );
    }
}