<?php

namespace App\Notifications;

use App\Events\NewNotificationEvent;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class NewInteractionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $type,
        public object $model,
        public string $message,
        public int $senderId

    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'message' => $this->message,
            'model_type' => get_class($this->model),
            'model_id' => $this->model->id,
            'sender_id' => $this->senderId,
        ];

    }
}
