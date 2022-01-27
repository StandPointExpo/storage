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
            return response('Forbidden.', 403);
        };
        if($request->header('ServiceToken') !== config('app.key')) {
            return response('Forbidden.', 403);
        };
        return $next($request);
    }
}
