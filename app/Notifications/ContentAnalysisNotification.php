<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContentAnalysisNotification extends Notification
{
    protected $status;
    protected $message;

    public function __construct($status, $message)
    {
        $this->status = $status;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database']; // إرسال الإشعار عبر قاعدة البيانات
    }

    public function toDatabase($notifiable)
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
        ];
    }
}
