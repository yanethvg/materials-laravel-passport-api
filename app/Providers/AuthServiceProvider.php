<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // descomment this list
        //  'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $expireToken =  env('TOKEN_EXPIRE_IN',"1");
        $expireToken = intval($expireToken);
        // passort routes
        // Passport::routes();
        Passport::personalAccessTokensExpireIn(now()->addDay($expireToken));
    }
}
