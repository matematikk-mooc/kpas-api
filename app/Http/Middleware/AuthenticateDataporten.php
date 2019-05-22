<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateDataporten
{
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('X-Dataporten-Token')) {
            throw new \Exception("Dataporten token not specified");
        }
        return $next($request);
    }
}
