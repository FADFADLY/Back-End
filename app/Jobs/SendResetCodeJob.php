<?php

namespace App\Jobs;

use App\Mail\ResetCodeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendResetCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $resetCode;

    public function __construct($email, $resetCode)
    {
        $this->email = $email;
        $this->resetCode = $resetCode;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new ResetCodeMail($this->resetCode, $this->email));
    }
}
