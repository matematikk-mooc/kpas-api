<?php

namespace App\Http\Middleware;

use App\Exceptions\LtiException;
use Closure;
use Illuminate\Http\Request;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $request_token = $request->bearerToken();
        $kpas_api_token = env('KPAS_API_ACCESS_TOKEN');
        if ($kpas_api_token == $request_token) {
            return $next($request);
        }
        else{
            throw new LtiException("Provided token is invalid ");
        }

    }
}
