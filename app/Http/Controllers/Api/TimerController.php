<?php

namespace App\Http\Controllers\Api;

use App\Models\Timer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimerController extends Controller
{
    use ApiResponse;
    public function startOrResume(Request $request)
    {
        $timer = Timer::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'related_type' => $request->related_type,
            ],
            ['status' => 'paused']
        );

        if ($timer->status === 'running') {
            return $this->errorResponse([],'تم تشغيل المؤقت بالفعل.');
        }

        $timer->update(['status' => 'running']);
        $timer->sessions()->create(['started_at' => now()]);

        return $this->successResponse([
            'timer_id' => $timer->id,
        ], 'تم تشغيل المؤقت بنجاح.');
    }

    public function pause($id)
    {
        $timer = Timer::findOrFail($id);

        if ($timer->status === 'paused') {
            return $this->errorResponse([],'المؤقت متوقف بالفعل.');
        }

        $session = $timer->sessions()->latest()->first();

        if ($session && !$session->ended_at && $session->started_at) {
            $endedAt = now();

            $duration = abs($endedAt->diffInSeconds($session->started_at));

            $session->update([
                'ended_at' => $endedAt,
                'duration_seconds' => $duration,
            ]);
        }

        $timer->update(['status' => 'paused']);

        return $this->successResponse([], 'تم إيقاف المؤقت بنجاح.');

    }

    public function stop($id)
    {
        $timer = Timer::findOrFail($id);

        $session = $timer->sessions()->latest()->first();

        if ($session && !$session->ended_at && $session->started_at) {
            $endedAt = now();

            $duration = abs($endedAt->diffInSeconds($session->started_at));

            $session->update([
                'ended_at' => $endedAt,
                'duration_seconds' => $duration,
            ]);
        }

        $timer->update(['status' => 'stopped']);

        return $this->successResponse([], 'تم إيقاف المؤقت بنجاح.');

    }

    public function duration($id)
    {
        $timer = Timer::with('sessions')->findOrFail($id);

        $totalSeconds = $timer->sessions->sum('duration_seconds');

       return $this->successResponse([
            'total_seconds' => $totalSeconds,
            'formatted_duration' => gmdate('H:i:s', $totalSeconds),
        ], 'تم استرجاع مدة المؤقت بنجاح.');
    }
}
