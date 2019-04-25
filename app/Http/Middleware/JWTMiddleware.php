<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Http\Helper\JWTHelper;
use Illuminate\Http\Request;

class JWTMiddleware {
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
        $access_token = JWTHelper::parseAuthHeader($request);
        $credentials = JWTHelper::authenticate($access_token, $request->ip());

        // Returns an error message for an invalid token
        if (isset($credentials->error)) {
            $http_code = $credentials->http;
            unset($credentials->http);
            return response()->json($credentials, $http_code);
        }

        // Put the user in the request class so that you can grab it from there
        $request->user = User::findOrFail($credentials->sub);

        return $next($request);
    }
}