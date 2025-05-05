<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimerSession extends Model
{
    use HasFactory;

    protected $fillable = ['timer_id', 'started_at', 'ended_at', 'duration_seconds'];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function timer()
    {
        return $this->belongsTo(Timer::class);
    }

    public function calculateDuration()
    {
        $started_at = Carbon::parse($this->started_at);
        $ended_at = Carbon::parse($this->ended_at);

        if ($ended_at && $started_at) {
            return $ended_at->diffInSeconds($started_at);
        }

        return 0;
    }
}
