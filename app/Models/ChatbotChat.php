<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotChat extends Model
{
    protected $table = 'chatbot_chats';

    protected $fillable = ['user_id', 'title'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatBotMessage::class);
    }

}
