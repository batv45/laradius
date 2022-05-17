<?php

namespace App\Observers;

use App\Models\Account;
use App\Services\Radius\RadiusService;

class AccountObserver
{
    public function created(Account $account)
    {
        app(RadiusService::class)->syncAccount($account);
    }

    public function updated(Account $account)
    {
        app(RadiusService::class)->syncAccount($account);
    }

    public function deleted(Account $account)
    {
        //  app(RadiusService::class)->deleteAccount($account);
    }

    public function restored(Account $account)
    {
        //  app(RadiusService::class)->deleteAccount($account);
    }

    public function forceDeleted(Account $account)
    {
        //  app(RadiusService::class)->deleteAccount($account);
    }
}
