<?php

namespace App\Notifications;

use App\Notifications\Channels\Dashboard\DashboardChannel;
use App\Notifications\Channels\Dashboard\DashboardMessage;
use Illuminate\Notifications\Notification;

class News extends Notification
{

    public string $title;
    public ?string $message;
    public function __construct(string $title,string $message=null)
    {
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DashboardChannel::class];
    }

    public function toDashboard($notifiable)
    {
        return (new DashboardMessage)
            ->title($this->title)
            ->message($this->message)
            ->action(url('/'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
        ];
    }
}
