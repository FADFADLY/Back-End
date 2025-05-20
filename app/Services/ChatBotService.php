<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class ChatBotService
{
    protected string $apiKey = 'SheYm4TB2qPFx4FOvdKBuWTcw2JR4i9X4gJmYdmT';
    protected string $model = 'cd064453-6dbe-4dc1-b62d-1381276d0a9c-ft';

    public function sendMessage(string $prompt): ?string
    {
        $response = Http::timeout(1000)->withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => 'bearer ' . $this->apiKey,
        ])->post('https://api.cohere.com/v2/chat', [
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => $prompt]
                    ]
                ]
            ],
            'temperature' => 0.3,
            'model' => $this->model,
        ]);

        if (!$response->successful()) {
            return null;
        }

        $contentArray = $response->json()['message']['content'] ?? [];
        $texts = array_map(fn($item) => $item['text'] ?? '', $contentArray);

        return trim(implode(" ", $texts));
    }
}
