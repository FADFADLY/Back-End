<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function show($testId)
    {
        $test = Test::select('id', 'name', 'description')->with(['questions.answers' => function ($query) {
            $query->select('id', 'answer', 'points', 'question_id');
        }])->findOrFail($testId);

        return response()->json($test);
    }

    public function calculateScore($testId, Request $request)
    {
        $test = Test::with('questions.answers')->findOrFail($testId);

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

        return response()->json([
            'score' => $score,
            'result' => $result,
        ]);
    }
}
