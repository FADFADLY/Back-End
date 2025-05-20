<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Test;
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
        $result = '';

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
        if ($score < 0) {
            return $this->errorResponse([],'الدرجة غير صحيحة', 400);
        }
        if ($testId == 1) {
            if ($score <= 9) {
                $result = 'قلق منخفض';
            } elseif ($score <= 16) {
                $result = 'قلق بسيط';
            } elseif ($score <= 25) {
                $result = 'قلق متوسط';
            } elseif ($score <= 32) {
                $result = 'قلق شديد';
            } else {
                $result = 'قلق شديد جداً';
            }
        } elseif ($testId == 2) {
            if ($score <= 9) {
                $result = 'لا يوجد اكتئاب';
            } elseif ($score <= 16) {
                $result = 'اكتئاب بسيط';
            } elseif ($score <= 25) {
                $result = 'اكتئاب متوسط';
            } elseif ($score <= 32) {
                $result = 'اكتئاب شديد';
            } else {
                $result = 'اكتئاب شديد جداً';
            }
        } elseif ($testId == 3) {
            if ($score <= 41) {
                $result = 'طبيعي';
            } else {
                $result = 'مرتفع';
            }
        }

        return $this->successResponse([
            'score' => $score,
            'result' => $result,
        ], 'تم حساب النتيجة بنجاح', 200);
    }
}
