<?php

namespace App\Listeners;

class OnlineCheck
{
    public function __construct()
    {
        //
    }

    public function handle($event)
    {
        if ($event->user !== null) {
            $event->user->setCache(config('session.lifetime'));
        }
    }
}
