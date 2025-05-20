<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Services\TestResultMessageService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $tests = Test::select('id', 'name')->get();

        if ($tests->isEmpty()) {
            return $this->errorResponse([],'لا توجد اختبارات متاحة', 404);
        }

        return $this->successResponse($tests, 'تم جلب الاختبارات بنجاح', 200);
    }

    public function show($testId)
    {
        $test = Test::select('id', 'name', 'description')
            ->with(['questions' => function($query) {
                $query->select('id', 'question', 'test_id')
                    ->with('answers:id,answer,question_id');
            }])
            ->find($testId);

        if (!$test) {
            return $this->errorResponse([], 'الاختبار غير موجود', 404);
        }

        if ($test->questions->isEmpty()) {
            return $this->errorResponse([], 'لا توجد أسئلة لهذا الاختبار', 404);
        }

        return $this->successResponse($test, 'تم جلب بيانات الاختبار والأسئلة بنجاح', 200);
    }

    public function calculateScore($testId, Request $request)
    {
        $test = Test::with('questions.answers')->findOrFail($testId);

        if (!$test) {
            return $this->errorResponse([],'الاختبار غير موجود', 404);
        }

        $score = 0;
        foreach ($test->questions as $question) {
            if ($request->has($question->id)) {
                $selectedAnswerId = $request->input($question->id);
                foreach ($question->answers as $answer) {
                    if ($answer->id == $selectedAnswerId) {
                        $score += $answer->points;
                        break;
                    }
                }
            }
        }
        $testId = (int) $testId;
        $resultData = match ($testId) {
            1 => TestResultMessageService::getAnxietyResult($score),      // تايلور
            2 => TestResultMessageService::getDepressionResult($score),   // بيك
            3 => TestResultMessageService::getSpenceResult($score),       // سبنس
            default => ['result' => 'غير معروف', 'message' => 'نوع الاختبار غير معروف'],
        };

        return $this->successResponse([
            'score' => $score,
            'result' => $resultData['result'],
            'message' => $resultData['message'],
            'image' => $resultData['image']
        ], 'تم حساب النتيجة بنجاح', 200);
    }
}
