<?php

namespace App\Providers;

use App\Http\Controllers\Api\V1\CrmUserController;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class CrmUserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        $authUser = new CrmUserController;
        $authUser->saveCrmUser($request);
    }
}
