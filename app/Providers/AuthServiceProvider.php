<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Domain\Faculty\Models\Faculty;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

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
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmailNotification::createUrlUsing(function (mixed $notifiable) {
            if ($notifiable instanceof Faculty) {
                return URL::temporarySignedRoute(
                    'faculty.verification.verify',
                    Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                    [
                        'faculty' => $notifiable->getKey(),
                        'hash' => sha1($notifiable->getEmailForVerification()),
                    ],
                );
            }
        });
    }
}
