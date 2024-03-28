<?php

namespace App\Providers;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Gate;
use App\Providers\AuthMemberProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

 // 以下を追加  
        \Auth::provider('member_auth', function($app, array $config) {
            return new AuthMemberProvider($this->app['hash'], $config['model']);
        });    }
}
