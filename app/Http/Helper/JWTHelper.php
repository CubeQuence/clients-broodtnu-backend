<?php

namespace App\Http\Helper;

use App\RefreshToken;
use Exception;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JWTHelper {
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function issue($user_id)
    {
        return [
            'access_token' => $this->issueAccessToken($user_id),
            'refresh_token' =>  $this->issueRefreshToken($user_id)
        ];
    }

    public function refresh($refresh_token)
    {
        $user_id = $this->validateRefreshToken($refresh_token);

        if (!$user_id) {
            return false;
        }

        $this->revokeRefreshToken($refresh_token);

        return $this->issueRefreshToken($user_id);
    }

    public function authenticate($access_token) {
        return $this->validateAccessToken($access_token);
    }

    public function logout($refresh_token)
    {
        $user_id = $this->validateRefreshToken($refresh_token);

        if (!$user_id) {
            return false;
        }

        $this->revokeRefreshToken($refresh_token);

        return true;
    }


    private function validateAccessToken($access_token = null) {
        if (!$access_token) {
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }

        try {
            $credentials = JWT::decode($access_token, config('JWT.public_key'), [config('JWT.algorithm')]);
        } catch (ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        }

        return $credentials;
    }

    private function validateRefreshToken($refresh_token = null) {
        if (!$refresh_token) {
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }

        $refresh_token = RefreshToken::where('refresh_token', $refresh_token)-first();

        if ($refresh_token->expires_at->isPast()) {
            return false;
        }

        return $refresh_token->user_id;
    }

    private function issueAccessToken($user_id) {
        $payload = [
            'iss' => env('APP_URL'),
            'sub' => $user_id,
            'iat' => time(),
            'exp' => time() + config('JWT.ttl.access_token')
        ];

        $access_token = JWT::encode($payload, config('JWT.private_key'), config('JWT.algorithm'));

        return $access_token;
    }

    private function issueRefreshToken($user_id) {
        $refresh_token = new RefreshToken();

        $refresh_token->user_id = $user_id;
        $refresh_token->refresh_token = 'RANDOM_TOKEN';
        $refresh_token->expires_at = time() + config('JWT.ttl.refresh_token');

        $refresh_token->save();

        return $refresh_token->refresh_token;
    }

    private function revokeRefreshToken($refresh_token) {
        RefreshToken::where('refresh_token', $refresh_token)->delete();
    }
}