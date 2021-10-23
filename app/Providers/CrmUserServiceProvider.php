<?php

namespace App\Providers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\CrmUserController;
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
