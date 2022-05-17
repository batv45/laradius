<?php

namespace App\Observers;

use App\Enums\RouterIPType;
use App\Models\RouterIP;

class RouterIPObserver
{
    public function creating(RouterIP  $routerIP)
    {
        if( $routerIP->type == RouterIPType::Lan ){
            $routerIP->account_limit = 0;
        }
    }
    public function created(RouterIP $routerIP)
    {
        //
    }

    public function updated(RouterIP $routerIP)
    {
        //
    }

    public function deleted(RouterIP $routerIP)
    {
        //
    }

    public function restored(RouterIP $routerIP)
    {
        //
    }

    public function forceDeleted(RouterIP $routerIP)
    {
        //
    }
}
