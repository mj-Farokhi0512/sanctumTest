<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $frontEndUrl = env('FRONTEND_URL');

        $this->setFrontEndUrlResetPasswordEmail($frontEndUrl);
    }

    protected function setFrontEndUrlResetPasswordEmail($frontEndUrl = '')
    {
        ResetPassword::createUrlUsing(function ($user, string $token) use ($frontEndUrl) {
            // return $frontEndUrl . '/reset-password?token=' . $token;
            return 'http://localhost:3000/auth/reset-password/' . $token;
        });
    }
}
