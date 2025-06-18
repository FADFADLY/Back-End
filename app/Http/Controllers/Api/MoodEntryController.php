<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoodEntry;
use App\Services\SentimentAnalysisService;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoodEntryController extends Controller
{
    use ApiResponse;
    public function store(Request $request, SentimentAnalysisService $service)
    {
        ini_set('max_execution_time', '1000');

        try {
            $validated = $request->validate([
                'mood'    => 'required|in:happy,neutral,sad,angry,crying',
                'feeling' => 'required|string',
                'notes'   => 'nullable|string',
            ],[
                'mood.required'    => 'الحالة المزاجية مطلوبة',
                'mood.in'          => 'الحالة المزاجية يجب أن تكون واحدة من: happy, neutral, sad, angry, crying',
                'feeling.required' => 'الشعور مطلوب',
                'notes.string'     => 'الملاحظات يجب أن تكون نصًا',
            ]);
        }
        catch (\Exception $e) {
            return $this->validationErrorResponse($e, [
                'mood',
                'feeling',
                'notes',
            ], 'خطأ في البيانات المدخلة');
        }

        $modelPrediction = $service->analyzeFeeling($request->feeling);

        if (!$modelPrediction) {
            return $this->errorResponse([],'فشل في تحليل الشعور', 500);
        }


        $moodEntry = MoodEntry::create([
            'user_id'    => auth()->id(),
            'entry_date' => now()->toDateString(),
            'mood'       => $validated['mood'],
            'model_prediction' => $modelPrediction,
            'feeling'    => $validated['feeling'],
            'notes'      => $validated['notes'],
        ]);

        if(!$moodEntry) {
            return $this->errorResponse([],'فشل في إنشاء الحالة المزاجية', 500);
        }

        return $this->successResponse(
            [],
            'تم إنشاء الحالة المزاجية بنجاح',
            201
        );


    }

    public function index(Request $request)
    {
        $query = MoodEntry::select('id', 'entry_date', 'mood', 'feeling', 'notes')
            ->where('user_id', auth()->id())->latest();

        if ($request->has('month')) {
            $month = (int) $request->input('month');
            $year = (int) $request->input('year', now()->year);

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
            $entry->day_of_week = Carbon::parse($entry->entry_date)->format('l');
            return $entry;
        });

        if ($moodEntries->isEmpty()) {
            return $this->errorResponse([],'لا توجد حالات مزاجية مسجلة', 404);
        }

        return $this->successResponse(
            $moodEntries,
            'تم استرجاع جميع الحالات المزاجية بنجاح',
            200
        );
    }

    public function show($id)
    {

        $moodEntry = MoodEntry::select('id', 'entry_date', 'mood', 'feeling', 'notes')->find($id);

        if (!$moodEntry) {
            return $this->errorResponse([],'الحالة المزاجية غير موجودة', 404);
        }

        $moodEntry->day_of_week = Carbon::parse($moodEntry->entry_date)->format('l');

        return $this->successResponse(
            $moodEntry,
            'تم استرجاع الحالة المزاجية بنجاح',
            200
        );
    }
}
