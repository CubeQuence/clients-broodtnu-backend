<?php

namespace App\Http\Helper;

use App\RefreshToken;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Exception;

class JWTHelper {
    public static function issue($user_id)
    {
        return [
            'access_token' => $this->issueAccessToken($user_id),
            'refresh_token' =>  $this->issueRefreshToken($user_id)
        ];
    }

    public static function refresh($refresh_token)
    {
        $user_id = JWTHelper::validateRefreshToken($refresh_token);

        if (!$user_id) {
            return false;
        }

        JWTHelper::revokeRefreshToken($refresh_token);

        return JWTHelper::issueRefreshToken($user_id);
    }

    public function authenticate($access_token) {
        return JWTHelper::validateAccessToken($access_token);
    }

    public static function logout($refresh_token)
    {
        $user_id = JWTHelper::validateRefreshToken($refresh_token);

        if (!$user_id) {
            return false;
        }

        JWTHelper::revokeRefreshToken($refresh_token);

        return true;
    }

    private static function validateAccessToken($access_token = null) {
        if (!$access_token) {
            return (object) [
                'error' => 'Access_token not provided.',
                'http' => 401
            ];
        }

        try {
            $credentials = JWT::decode($access_token, config('JWT.public_key'), [config('JWT.algorithm')]);
        } catch (ExpiredException $error) {
            return (object) [
                'error' => 'Access_token has expired.',
                'http' => 400
            ];
        } catch (Exception $error) {
            return (object) [
                'error' => 'Access_token has invalid signature.',
                'http' => 400
            ];
        }

        return $credentials;
    }

    private static function validateRefreshToken($refresh_token = null) {
        if (!$refresh_token) {
            return false;
        }

        $refresh_token = RefreshToken::where('refresh_token', $refresh_token)-first();

        if (!$refresh_token || $refresh_token->expires_at->isPast()) {
            return false;
        }

        return $refresh_token->user_id;
    }

    private static function issueAccessToken($user_id) {
        $payload = [
            'iss' => env('APP_URL'),
            'sub' => $user_id,
            'iat' => time(),
            'exp' => time() + config('JWT.ttl.access_token')
        ];

        $access_token = JWT::encode($payload, config('JWT.private_key'), config('JWT.algorithm'));

        return $access_token;
    }

    private static function issueRefreshToken($user_id) {
        $refresh_token = new RefreshToken();

        $refresh_token->user_id = $user_id;
        $refresh_token->refresh_token = Str::random(32);
        $refresh_token->expires_at = time() + config('JWT.ttl.refresh_token');

        $refresh_token->save();

        return $refresh_token->refresh_token;
    }

    private static function revokeRefreshToken($refresh_token) {
        RefreshToken::where('refresh_token', $refresh_token)->delete();
    }
}