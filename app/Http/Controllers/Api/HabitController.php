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
        $habits = Habit::select('id', 'name', 'icon')
            ->get()
            ->map(function ($habit) {;
                    return [
                        'id' => $habit->id,
                        'name' => $habit->name,
                        'icon' => $habit->icon ? asset('storage/' . $habit->icon) : null,
                    ];
                });

        if ($habits->isEmpty()) {
            return $this->errorResponse('لا توجد عادات', 404);
        }
         return $this->successResponse($habits,'تم جلب العادات بنجاح');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validated =  request()->validate([
           'habits' => 'required|array',
        ],[
            'habits.required' => 'يجب اختيار عادات',
            'habits.array' => 'يجب أن تكون العادات مصفوفة',
        ]);

      $habits = $validated['habits'];
      $user = auth()->user();

      $habitsScore = HabitsScore::Create(
          [
              'user_id' => $user->id,
              'habits' => json_encode($habits),
              'score' => count($habits),
          ]
         );

        if (!$habitsScore) {
            return $this->errorResponse([],'حدث خطأ أثناء حفظ العادات', 500);
        }

        return $this->successResponse([
            'score' => count($habits),
            'full_mark' => count(Habit::all()),
        ], 'تم حفظ العادات بنجاح');
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
