<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatBotMessage;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatBotController extends Controller
{
    use ApiResponse;
    public function sendToChatbot(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://ahmedelsherbeny-fadfadly-demo.hf.space/generate', [
            'prompt' => $request->prompt,
        ]);

        if ($response->successful()) {
            ChatBotMessage::create([
                'user_id' => auth()->id(),
                'prompt' => $request->prompt,
                'response' => $response,
            ]);
            return $this->successResponse([
                'response' => $response->json(),
            ], 'تم إرسال الرسالة بنجاح');
        } else {
            return $this->errorResponse([], 'خطأ في إرسال الرسالة', 500);
        }
    }
}
