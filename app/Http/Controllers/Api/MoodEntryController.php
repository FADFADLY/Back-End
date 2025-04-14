<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoodEntry;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MoodEntryController extends Controller
{
    use ApiResponse;
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mood'    => 'required|in:happy,neutral,sad,angry,crying',
            'feeling' => 'nullable|string',
            'notes'   => 'nullable|string',
        ]);

        $moodEntry = MoodEntry::create([
            'user_id'    => auth()->id(),
            'entry_date' => now()->toDateString(),
            'mood'       => $validated['mood'],
            'feeling'    => $validated['feeling'],
            'notes'      => $validated['notes'],
        ]);

        if(!$moodEntry) {
            return $this->errorResponse('فشل في إنشاء الحالة المزاجية', 500);
        }

        return $this->successResponse(
            null,
            'تم إنشاء الحالة المزاجية بنجاح',
            201
        );


    }

    public function index(Request $request)
    {
        $query = MoodEntry::select('id', 'entry_date', 'mood', 'feeling', 'notes')
            ->where('user_id', auth()->id());

        if ($request->has('month')) {
            $month = (int) $request->input('month');
            $year = (int) $request->input('year', now()->year); // لو السنة مش متبعتش، يستخدم السنة الحالية

            $query->whereMonth('entry_date', $month)
                ->whereYear('entry_date', $year);
        }

        if ($request->has('week')) {
            $week = (int) $request->input('week');
            $year = (int) $request->input('year', now()->year);

            $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
            $endOfWeek = Carbon::now()->setISODate($year, $week)->endOfWeek();

            $query->whereBetween('entry_date', [$startOfWeek, $endOfWeek]);
        }

        $moodEntries = $query->get();

        $moodEntries->map(function ($entry) {
            $entry->day_of_week = Carbon::parse($entry->entry_date)->format('D'); // مثلاً "Monday"
            return $entry;
        });

        if ($moodEntries->isEmpty()) {
            return $this->errorResponse('لا توجد حالات مزاجية مسجلة', 404);
        }

        return $this->successResponse(
            $moodEntries,
            'تم استرجاع جميع الحالات المزاجية بنجاح',
            200
        );
    }

    public function show($id)
    {

        $moodEntry = MoodEntry::find($id)->select('id', 'entry_date', 'mood', 'feeling', 'notes')->first();

        if (!$moodEntry) {
            return $this->errorResponse('الحالة المزاجية غير موجودة', 404);
        }

        $moodEntry->day_of_week = Carbon::parse($moodEntry->entry_date)->format('l');

        return $this->successResponse(
            $moodEntry,
            'تم استرجاع الحالة المزاجية بنجاح',
            200
        );
    }
}
