<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CrmServiceAuthentificate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if(!$request->header('ServiceToken')) {
            return response('Unauthorized.', 401);
        };
        if($request->header('ServiceToken') !== config('app.app_service_token')) {
            return response('Unauthorized.', 401);
        };
        return $next($request);
    }
}
