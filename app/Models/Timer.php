<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'related_type', 'related_id', 'status'];

    public function sessions()
    {
        return $this->hasMany(TimerSession::class);
    }

    public function totalDuration()
    {
        return $this->sessions()->sum('duration_seconds');
    }
}
