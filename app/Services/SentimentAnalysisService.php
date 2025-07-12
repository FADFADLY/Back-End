<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SentimentAnalysisService
{
    protected string $baseUrl = 'https://bedourfouad-arabic-sentiment-demo.hf.space/gradio_api/call';

    public function analyzeFeeling(string $text): ?string
    {
        try {
            $initialResponse = Http::timeout(1000)->post($this->baseUrl . '/generate_sentiment_label', [
                'data' => [$text],
            ]);

            $eventId = $initialResponse->json()['event_id'] ?? null;
            if (!$eventId) {
                return null;
            }
            $resultResponse = Http::timeout(1000)->get($this->baseUrl . "/generate_sentiment_label/{$eventId}");

            if (!$resultResponse->ok()) {
                return null;
            }
            preg_match('/data:\s*(\[.*\])/', $resultResponse->body(), $matches);

            if (isset($matches[1])) {
                $decoded = json_decode($matches[1], true);
                return $decoded[0] ?? null;
            }
            return null;

        } catch (\Exception $e) {
            return null;
        }
    }
}
