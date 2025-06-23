<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitsScore;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class HabitController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        // كل العادات المتاحة
        $allHabits = Habit::all();

        // العادات اللي خلصها النهارده (إن وجدت)
        $todayScore = HabitsScore::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->first();

        $doneHabits = $todayScore
            ? json_decode($todayScore->habits, true)
            : [];

        // نرجع كل عادة ومعاها if it's done today
        $habitsWithStatus = $allHabits->map(function ($habit) use ($doneHabits) {
            return [
                'id' => $habit->id,
                'name' => $habit->name,
                'icon' => $habit->icon ? asset('storage/' . $habit->icon) : null,
                'done_today' => in_array($habit->id, $doneHabits),
            ];
        });

        return $this->successResponse($habitsWithStatus, 'تم جلب العادات بنجاح');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'habits' => 'required|array',
        ], [
            'habits.required' => 'يجب اختيار عادات',
            'habits.array' => 'يجب أن تكون العادات مصفوفة',
        ]);

        $habits = $validated['habits'];
        $user = auth()->user();
        $today = now()->toDateString();

        // نحاول نلاقي سجل النهاردة
        $todayScore = HabitsScore::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->first();

        $mergedHabits = null;
        if ($todayScore) {
            // لو فيه سجل، ندمج العادات الجديدة مع القديمة (بدون تكرار)
            $existingHabits = json_decode($todayScore->habits, true);
            $mergedHabits = array_unique(array_merge($existingHabits, $habits));
            $todayScore->update([
                'habits' => json_encode($mergedHabits),
                'score' => count($mergedHabits),
            ]);
        } else {
            // لو مفيش، نعمل سجل جديد
            $todayScore = HabitsScore::create([
                'user_id' => $user->id,
                'habits' => json_encode($habits),
                'score' => count($habits),
            ]);
        }

        if (!$todayScore) {
            return $this->errorResponse([], 'حدث خطأ أثناء حفظ العادات', 500);
        }

        return $this->successResponse([
            'score' => $todayScore->score,
            'full_mark' => Habit::count(),
            'done_habits' =>  $mergedHabits,
        ], 'تم تحديث العادات بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
