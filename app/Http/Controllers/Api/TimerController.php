<?php

namespace App\Http\Controllers\Api;

use App\Models\Timer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimerController extends Controller
{
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
            return response()->json(['message' => 'Timer is already running.']);
        }

        $timer->update(['status' => 'running']);
        $timer->sessions()->create(['started_at' => now()]);

        return response()->json([
            'message' => 'Timer started/resumed.',
            'timer_id' => $timer->id
        ]);
    }

    public function pause($id)
    {
        $timer = Timer::findOrFail($id);

        if ($timer->status === 'paused') {
            return response()->json(['message' => 'Timer is already paused.']);
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

        return response()->json(['message' => 'Timer paused.']);
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

        return response()->json(['message' => 'Timer stopped.']);
    }

    public function duration($id)
    {
        $timer = Timer::with('sessions')->findOrFail($id);

        $totalSeconds = $timer->sessions->sum('duration_seconds');

        return response()->json([
            'message' => 'Total duration fetched successfully.',
            'duration_seconds' => $totalSeconds
        ]);
    }
}
