<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitsScore extends Model
{
    protected $fillable = [
        'user_id',
        'score',
        'habits',
    ];

    protected $casts = [
        'habits' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
