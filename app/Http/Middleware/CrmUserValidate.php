<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\CrmUserController;

class CrmUserValidate
{
    private $crmUserController;

    public function __construct(CrmUserController $crmUserController) {
        $this->crmUserController = new CrmUserController;
    }
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$this->crmUserController->getCrmUserToken($request)) {
            return response('Unauthorized.', 401);
        };
        return $next($request);
    }
}
