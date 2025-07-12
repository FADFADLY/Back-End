<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ChatBotMessage extends Model
{
    protected $table = 'chat_bot_messages';

    protected $fillable = ['chat_id', 'prompt', 'response'];


    public function chat()
    {
        return $this->belongsTo(ChatbotChat::class);
    }

    public function setPromptAttribute($value)
    {
        $this->attributes['prompt'] = Crypt::encryptString($value);
    }

    public function getPromptAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function setResponseAttribute($value)
    {
        $this->attributes['response'] = Crypt::encryptString($value);
    }

    public function getResponseAttribute($value)
    {
        return Crypt::decryptString($value);
    }

}
