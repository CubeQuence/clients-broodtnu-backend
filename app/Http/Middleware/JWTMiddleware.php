<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;

class JWTMiddleware {

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $this->parseAuthHeader('Authorization', 'Bearer');

        if (!$token) {
            // TODO: Add helper to assure same style messages.
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }

        try {
            $credentials = JWT::decode($token, config('JWT.public_key'), [config('JWT.algorithm')]);
        } catch (ExpiredException $e) {
            // TODO: Add helper to assure same style messages.
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch (Exception $e) {
            // TODO: Add helper to assure same style messages.
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        }

        // Now let's put the user in the request class so that you can grab it from there
        $user = User::find($credentials->sub);
        $request->auth = $user;

        return $next($request);
    }

    /**
     * Parse token from the authorization header.
     *
     * @param string $header
     * @param string $method
     *
     * @return false|string
     */
    protected function parseAuthHeader($header, $method)
    {
        $headerValue = $this->request->headers->get($header);

        if (strpos($headerValue, $method) === false) {
            return false;
        }

        return trim(str_ireplace($method, '', $headerValue));
    }
}