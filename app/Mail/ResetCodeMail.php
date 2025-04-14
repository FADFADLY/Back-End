<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $resetCode;
    public $email;

    public function __construct($resetCode, $email)
    {
        $this->resetCode = $resetCode;
        $this->email     = $email;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'رمز التحقق الخاص بك - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'otp' => $this->resetCode,
                'email' => $this->email,
            ],
        );
    }
}
