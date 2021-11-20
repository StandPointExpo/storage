<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\V1\CrmUserController;
use Closure;
use Illuminate\Http\Request;
use App\Models\User;

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
