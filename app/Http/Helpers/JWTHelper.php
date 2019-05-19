<?php

namespace App\Http\Helpers;

use App\Models\RefreshToken;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Exception;

class JWTHelper {
    /**
     * Parses Authorization header
     *
     * @param Request $request
     *
     * @return bool|string
     */
    public static function parseAuthHeader(Request $request) {
        $header_value = $request->headers->get('Authorization');

        if (strpos($header_value, 'Bearer') === false) {
            return false;
        }

        return trim(str_ireplace('Bearer', '', $header_value));
    }

    /**
     * Creates a new set of access en refresh tokens
     *
     * @param int $user_id
     * @param string $user_ip
     *
     * @return array
     */
    public static function issue($user_id, $user_ip)
    {
        return [
            'access_token' =>JWTHelper::issueAccessToken($user_id, $user_ip),
            'refresh_token' =>  JWTHelper::issueRefreshToken($user_id)
        ];
    }

    /**
     * Refreshes the access_token, and provides a new refresh_token
     *
     * @param string $refresh_token
     * @param string $user_ip
     *
     * @return bool|array
     */
    public static function refresh($refresh_token, $user_ip)
    {
        $user_id = JWTHelper::validateRefreshToken($refresh_token);

        if (!$user_id) {
            return false;
        }

        JWTHelper::revokeRefreshToken($refresh_token);

        return JWTHelper::issue($user_id, $user_ip);
    }

    /**
     * Checks if access_token is valid
     * If valid returns the token payload
     *
     * @param string $access_token
     * @param string $user_ip
     *
     * @return object
     */
    public static function authenticate($access_token, $user_ip) {
        return JWTHelper::validateAccessToken($access_token, $user_ip);
    }

    /**
     * Logs user out
     *
     * @param string $refresh_token
     *
     * @return bool|null
     */
    public static function logout($refresh_token)
    {
        if (!JWTHelper::validateRefreshToken($refresh_token)) {
            return false;
        }

        return JWTHelper::revokeRefreshToken($refresh_token);
    }

    /**
     * Validates a access_token
     *
     * @param string $access_token
     * @param string $user_ip
     *
     * @return object
     */
    private static function validateAccessToken($access_token, $user_ip) {
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
                'http' => 401
            ];
        } catch (Exception $error) {
            return (object) [
                'error' => 'Access_token has invalid signature.',
                'http' => 401
            ];
        }

        if ($user_ip !== $credentials->sub_ip) {
            return (object) [
                'error' => 'Origin IP doesnt match sub_ip',
                'http' => 401
            ];
        }

        return $credentials;
    }

    /**
     * Validates a refresh_token
     *
     * @param string $refresh_token
     *
     * @return bool|int
     */
    private static function validateRefreshToken($refresh_token) {
        if (!$refresh_token) {
            return false;
        }

        $refresh_token = RefreshToken::where('refresh_token', $refresh_token)->first();

        if (!$refresh_token || $refresh_token->expires_at->isPast()) {
            return false;
        }

        return $refresh_token->user_id;
    }

    /**
     * Creates an access_token for a user
     *
     * @param int $user_id
     * @param string $user_ip
     *
     * @return string
     */
    private static function issueAccessToken($user_id, $user_ip) {
        $payload = [
            'iss' => config('JWT.iss'),
            'aud' => config('JWT.aud'),
            'sub' => $user_id,
            'sub_ip' => $user_ip,
            'iat' => time(),
            'exp' => time() + config('JWT.ttl.access_token')
        ];

        return JWT::encode($payload, config('JWT.private_key'), config('JWT.algorithm'));
    }

    /**
     * Creates an refresh_token for a user
     *
     * @param int $user_id
     *
     * @return string
     */
    private static function issueRefreshToken($user_id) {
        $refresh_token = new RefreshToken();

        $refresh_token->user_id = $user_id;
        $refresh_token->refresh_token = Str::random(32);
        $refresh_token->expires_at = time() + config('JWT.ttl.refresh_token');

        $refresh_token->save();

        return $refresh_token->refresh_token;
    }

    /**
     * Revoke a refresh_token
     *
     * @param string $refresh_token
     *
     * @return bool|null
     */
    private static function revokeRefreshToken($refresh_token) {
        return RefreshToken::where('refresh_token', $refresh_token)->delete();
    }

    /**
     * Delete all refresh_tokens of a user,
     * For example a password change, or deleted account
     *
     * @param $user_id
     *
     * @return mixed
     */
    public static function revokeAllRefreshTokens($user_id) {
        return RefreshToken::where('user_id', $user_id)->delete();
    }
}