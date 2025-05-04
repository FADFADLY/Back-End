<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatBotMessage extends Model
{
    protected $table = 'chat_bot_messages';

    protected $fillable = ['user_id', 'prompt', 'response'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
