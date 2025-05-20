<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    protected $fillable = ['answer', 'points'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    protected $casts = [
        'id' => 'integer',
        'question_id' => 'integer',
    ];

}
