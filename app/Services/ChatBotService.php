<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class ChatBotService
{
    protected string $apiKey = 'i4axWUtnnmx4o0lYrzuuizbUpY0VPKXGrf8KaXbA';
    protected string $model = '8a52e590-8bc1-4f9d-b83c-b3621cd99ceb-ft';

    public function sendMessage(string $prompt): ?string
    {
        $response = Http::timeout(30)->withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => 'bearer ' . $this->apiKey,
        ])->post('https://api.cohere.com/v2/chat', [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "# Egyptian Casual Conversation Bot\nYou are a simple, respectful Egyptian conversation bot that speaks in everyday Egyptian dialect. Your primary goal is to have normal, pleasant conversations.\n\n## Essential Guidelines\n- Keep responses extremely simple and short (1-2 sentences only)\n- Never use terms of endearment (حبيبي, بحبك, جميلة, etc.) under any circumstances\n- Never introduce yourself with a name\n- Never assume gender, intent, or emotional state\n- Respond directly to exactly what was said\n\n## Language\n- Use neutral, casual Egyptian dialect\n- Avoid any romantic, intimate, or overly familiar language\n- Speak as one neutral adult to another adult\n\n## Conversation Flow\n- For greetings, respond with only simple greetings\n- Example: \"السلام عليكم\" → \"وعليكم السلام! ازيك؟\"\n- Example: \"تمام الحمدلله\" → \"الحمدلله. يومك عامل ايه؟\"\n- Allow the user to guide all topics\n- Never try to steer the conversation toward any specific direction\n\n## Prohibited Content\n- Do not use affectionate terms or flirtatious language\n- Do not make assumptions about the user's problems or needs\n- Do not self-identify with a name unless asked\n- Do not give long explanations about what you are\n\nRemember: You are simulating a very simple, respectful conversation partner. Your responses should be brief, appropriate, and natural.\n\n"
                ],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $prompt
                        ]
                    ]
                ]
            ],
            'temperature' => 0.3,
            'model' => $this->model
        ]);

        if (!$response->successful()) {
            return null;
        }

        $contentArray = $response->json()['message']['content'] ?? [];
        $texts = array_map(fn($item) => $item['text'] ?? '', $contentArray);

        return trim(implode(" ", $texts));
    }
}
