<?php

namespace App\Http\Middleware;

use Closure;

class ApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('APP_ENV') === 'production') {
            $token = $request->header('Authorization');
            $valid = 'Basic ' . base64_encode('dataporten:' . config('dataporten.gatekeeper_password'));

            if ($token !== $valid) {
                return response()->json('Unauthorized', 401);
            }
            return $next($request);
        }
        return $next($request);
    }
}

