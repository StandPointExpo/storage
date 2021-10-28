<?php

namespace App\Providers;

use App\Models\CrmUser;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('crm-token', function (Request $request) {
            return CrmUser::whereHas('crmToken', function ($query) use ($request) {
                $query->where('token', $request->bearerToken());
            })
                ->first();
        });
    }
}
