<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class NotificationController extends Controller
{

    public function markAsRead($notif)
    {
        $user = auth()->user();

        $notifData = $user->unreadNotifications->find($notif);

        if($notifData){
            $notifData->markAsRead();
            if($notifData->data['action'] != null){
                return redirect($notifData->data['action']);
            }
        }
        return redirect()->back();
    }

    public function markAllRead(): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        $user->notifications->markAsRead();
        return redirect()->back();
    }

    public function deleteAll(): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        $user->notifications()->delete();
        return redirect()->back();
    }
}
