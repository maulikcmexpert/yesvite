<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Passport::routes();


        //
        // Passport::routes();
        // Passport::tokensExpireIn(now()->addDays(7)); // Adjust token expiration as needed
        // Passport::refreshTokensExpireIn(now()->addDays(30)); // Adjust refresh token expiration as needed
        // Passport::personalAccessTokensExpireIn(now()->addMonths(6));

    }
}
