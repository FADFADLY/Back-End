<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PostAnalysisService
{
    protected string $baseUrl = 'https://ayaibrahem12-arabic-sentiment-v2.hf.space';

    public function analyze(string $text): array
    {
        try {

            $startResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/gradio_api/call/predict', [
                'data' => [$text],
            ]);

            if (!$startResponse->successful()) {
                return [
                    'success' => false,
                    'message' => 'فشل في إرسال النص للموديل',
                ];
            }

            $eventId = $startResponse->json()['event_id'] ?? null;

            if (!$eventId) {
                return [
                    'success' => false,
                    'message' => 'لم يتم الحصول على event_id',
                ];
            }

            $resultResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/gradio_api/call/predict/' . $eventId);

            if (!$resultResponse->successful()) {
                return [
                    'success' => false,
                    'message' => 'فشل في الحصول على نتيجة التحليل',
                ];
            }

            $body = $resultResponse->body();

            if (preg_match('/data:\s*\[(.*?)\]/', $body, $matches)) {
                $jsonString = '[' . $matches[1] . ']';
                $dataArray = json_decode($jsonString, true);
                $fullText = $dataArray[0] ?? null;

                if ($fullText && str_contains($fullText, 'negative')) {
                    return [
                        'success' => false,
                        'message' => 'المحتوى مرفوض',
                    ];
                }

                return [
                    'success' => true,
                    'message' => 'تم قبول المحتوى',
                ];
            }

            return [
                'success' => false,
                'message' => 'لم يتم العثور على نتيجة التحليل',
            ];


        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء التحليل: ' . $e->getMessage(),
            ];
        }
    }
}
