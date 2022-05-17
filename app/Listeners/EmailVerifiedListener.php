<?php

namespace App\Listeners;

use App\Notifications\News;
use Illuminate\Auth\Events\Verified;

class EmailVerifiedListener
{
    public function __construct()
    {
        //
    }

    public function handle(Verified $verified)
    {
        $verified->user->notify(new News('Yeni E-Posta Onayı','Yeni E-Posta adresiniz onaylandı.'));
    }
}
