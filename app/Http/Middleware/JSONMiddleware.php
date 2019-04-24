<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JSONMiddleware {

    /**
     * If requests contains JSON interpret it.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */

    public function handle(Request $request, Closure $next)
    {
        if ($request->isJson()) {
            $data = $request->json()->all();
            $request->request->replace(is_array($data) ? $data : []);
        }

        return $next($request);
    }
}
