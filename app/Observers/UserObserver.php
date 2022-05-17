<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\News;
use Deligoez\TCKimlikNo\TCKimlikNo;

class UserObserver
{


    /**
     * Handle the user "created" event.
     *
     * @param User $user
     */
    public function created(User $user)
    {
        $user->syncRoles('user');
        $user->notify(new News('Yeni Hesap','Hesabınız oluşturuldu.'));
    }


    public function updating(User $user)
    {
    }
    /**
     * Handle the user "updated" event.
     *
     * @param User $user
     */
    public function updated(User $user)
    {
        $user->flushQueryCache();
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param User $user
     */
    public function deleted(User $user)
    {
        $user->flushQueryCache();
    }

    /**
     * Handle the user "restored" event.
     *
     * @param User $user
     */
    public function restored(User $user)
    {
        $user->flushQueryCache();
        $user->notify(new News('Hesap Silinmesi','Hesabınız silinmiş durumdan geri alındı.'));
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param User $user
     */
    public function forceDeleted(User $user)
    {
        $user->flushQueryCache();
    }
}
