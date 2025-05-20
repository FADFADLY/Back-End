<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatBotMessage extends Model
{
    protected $table = 'chat_bot_messages';

    protected $fillable = ['chat_id', 'prompt', 'response'];


    public function chat()
    {
        return $this->belongsTo(ChatbotChat::class);
    }

}
