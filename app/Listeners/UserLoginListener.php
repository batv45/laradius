<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Carbon;

class UserLoginListener
{
    public function __construct()
    {
        //
    }

    public function handle(Login $event)
    {
        $event->user->syncBalanceHistory();
        $event->user->update([
            'last_login_at' => Carbon::now(),
           'last_login_ip_address' => request()->getClientIp()
        ]);
    }
}
