<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class NewNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $notification;
    public $username;

    public function __construct($notification, $username)
    {
        $this->notification = $notification;
        $this->username = $username;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->username);
    }

    public function broadcastAs()
    {
        return 'new-notification';
    }
}
