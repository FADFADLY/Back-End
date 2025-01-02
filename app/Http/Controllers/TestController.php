<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function show($testId)
    {
        $test = Test::with('questions.answers')->findOrFail($testId);
        return response()->json($test);
    }
    public function storeResults(Request $request, $testId)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
        ]);

        $test = Test::findOrFail($testId);

        $totalPoints = 0;

        foreach ($validated['answers'] as $questionId => $answerId) {
            $answer = $test->questions()->find($questionId)->answers()->find($answerId);
            if ($answer) {
                $totalPoints += $answer->points;
            }
        }

        if ($test->name == 'Beck Depression Inventory') {
            $depressionLevelMessage = $this->getBeckDepressionLevelMessage($totalPoints);
        } elseif ($test->name == 'Taylor test for anxiety and depression') {
            $anxietyDepressionMessage = $this->getTaylorAnxietyDepressionLevelMessage($totalPoints);
        }

        return response()->json([
            'message'                  => 'Test completed successfully',
            'total_points'             => $totalPoints,
            'depression_level'         => $depressionLevelMessage ?? null,
            'anxiety_depression_level' => $anxietyDepressionMessage ?? null,
        ]);
    }

    private function getBeckDepressionLevelMessage($totalPoints)
    {
        if ($totalPoints >= 1 && $totalPoints <= 10) {
            return 'These ups and downs are considered normal';
        } elseif ($totalPoints >= 11 && $totalPoints <= 16) {
            return 'Mild mood disturbance';
        } elseif ($totalPoints >= 17 && $totalPoints <= 20) {
            return 'Borderline clinical depression';
        } elseif ($totalPoints >= 21 && $totalPoints <= 30) {
            return 'Moderate depression';
        } elseif ($totalPoints >= 31 && $totalPoints <= 40) {
            return 'Severe depression';
        } else {
            return 'Extreme depression';
        }
    }

    private function getTaylorAnxietyDepressionLevelMessage($totalPoints)
    {
        if ($totalPoints <= 10) {
            return 'Low anxiety level';
        } elseif ($totalPoints <= 20) {
            return 'Moderate anxiety level';
        } elseif ($totalPoints <= 30) {
            return 'High anxiety level';
        } else {
            return 'Severe anxiety level';
        }
    }
}
