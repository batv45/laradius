<?php

namespace App\Providers;

use App\Listeners\EmailVerifiedListener;
use App\Listeners\UserLoginListener;
use App\Models\Account;
use App\Models\Router;
use App\Models\RouterIP;
use App\Models\User;
use App\Observers\AccountObserver;
use App\Observers\RouterIPObserver;
use App\Observers\RouterObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \Illuminate\Auth\Events\Authenticated::class => [
            \App\Listeners\OnlineCheck::class
        ],
        Login::class => [
            UserLoginListener::class,
        ],
        Verified::class => [
            EmailVerifiedListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Account::observe(AccountObserver::class);
        Router::observe(RouterObserver::class);
        RouterIP::observe(RouterIPObserver::class);
    }
}
