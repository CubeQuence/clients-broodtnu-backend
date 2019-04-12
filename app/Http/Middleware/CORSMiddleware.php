<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CORSMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin'  => implode(", ", config('cors.allow_origins')),
            'Access-Control-Allow-Methods' => implode(", ", config('cors.allow_headers')),
            'Access-Control-Allow-Headers' => implode(", ", config('cors.allow_methods')),
        ];

        $response = $next($request);
        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }
}
