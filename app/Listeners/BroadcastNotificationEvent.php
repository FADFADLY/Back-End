<?php

namespace App\Listeners;

use App\Events\NewNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\InteractsWithQueue;

class BroadcastNotificationEvent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */

    public function handle(NotificationSent $event)
    {
        if ($event->channel === 'database') {
            $notification = $event->notification;
            broadcast(new NewNotificationEvent(
                $notification->toDatabase($event->notifiable),
                $event->notifiable->username
            ));
        }
    }

}
