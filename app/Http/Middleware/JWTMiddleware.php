<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Http\Helper\JWTHelper;
use Illuminate\Http\Request;

class JWTMiddleware {

    /**
     * Add JWTHelper so it can be accessed
     *
     * @param JWTHelper   $jwt
     */
    public function __construct(JWTHelper $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * Validate JWT token
     *
     * @param Request   $request
     * @param Closure   $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $access_token = $this->parseAuthHeader($request);
        $credentials = $this->jwt->authenticate($access_token);

        if (isset($credentials->error)) {
            $http_code = $credentials->http;
            unset($credentials->http);
            return response()->json($credentials, $http_code);
        }

        // Put the user in the request class so that you can grab it from there
        $user = User::find($credentials->sub);
        $request->auth = $user;

        return $next($request);
    }

    /**
     * Parse token from the authorization header
     *
     * @param Request   $request
     *
     * @return false|string
     */
    private function parseAuthHeader(Request $request)
    {
        $header_value = $request->headers->get('Authorization');

        if (strpos($header_value, 'Bearer') === false) {
            return false;
        }

        return trim(str_ireplace('Bearer', '', $header_value));
    }
}