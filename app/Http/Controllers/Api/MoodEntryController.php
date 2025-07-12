<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MoodEntryResource;
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
                'notes'   => 'nullable|string',
            ],[
                'notes.string'     => 'الملاحظات يجب أن تكون نصًا',
            ]);
        }
        catch (\Exception $e) {
            return $this->validationErrorResponse($e, [
                'notes',
            ], 'خطأ في البيانات المدخلة');
        }

        $modelPrediction = $service->analyzeFeeling($request->notes);

        if (!$modelPrediction) {
            return $this->errorResponse([],'فشل في تحليل الشعور', 500);
        }

        $moodEntry = MoodEntry::create([
            'user_id'    => auth()->id(),
            'entry_date' => now()->toDateString(),
            'model_prediction' => $modelPrediction,
            'notes'      => $validated['notes'],
        ]);

        if(!$moodEntry) {
            return $this->errorResponse([],'فشل في إنشاء الحالة المزاجية', 500);
        }

        return $this->successResponse(
            new MoodEntryResource($moodEntry),
            'تم إنشاء الحالة المزاجية بنجاح',
            201
        );

    }

    public function index(Request $request)
    {
        $moodEntries = MoodEntry::where('user_id', auth()->id())
            ->filterByDate($request)
            ->latest()
            ->get();

        if ($moodEntries->isEmpty()) {
            return $this->errorResponse([], 'لا توجد حالات مزاجية مسجلة', 404);
        }

        return $this->successResponse( MoodEntryResource::collection($moodEntries), 'تم استرجاع جميع الحالات المزاجية بنجاح', 200);
    }

    public function show($id)
    {

        $moodEntry = MoodEntry::find($id);

        if (!$moodEntry) {
            return $this->errorResponse([],'الحالة المزاجية غير موجودة', 404);
        }

        if ($moodEntry->user_id !== auth()->id()) {
            return $this->errorResponse([],'ليس لديك صلاحية للوصول إلى هذه الحالة المزاجية', 403);
        }

        $moodEntry->day_of_week = Carbon::parse($moodEntry->entry_date)->format('D');

        return $this->successResponse(
            new MoodEntryResource($moodEntry),
            'تم استرجاع الحالة المزاجية بنجاح',
            200
        );
    }
}
