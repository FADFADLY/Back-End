<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatbotChat;
use App\Models\ChatBotMessage;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\ChatBotService;

class ChatBotController extends Controller
{
    use ApiResponse;

    public function sendToChatbot(Request $request, ChatBotService $chatService)
    {
        ini_set('max_execution_time', '1000');

        $request->validate([
            'chat_id' => 'nullable|exists:chatbot_chats,id',
            'prompt' => 'required|string',
        ],[
            'chat_id.exists' => 'المحادثة غير موجودة',
            'prompt.required' => 'الرسالة مطلوبة',
        ]);

        $chat = null;
        if(!request()->has('chat_id')) {
            $chat = ChatBotChat::create([
                'user_id' => auth()->id(),
                'title' => $request->prompt,
            ]);
        } else {
            $chat = ChatbotChat::find($request->chat_id);
            if (!$chat) {
                return $this->errorResponse([], 'المحادثة غير موجودة', 404);
            }
        }

        $reply = $chatService->sendMessage($request->prompt);

        if (!$reply) {
            return $this->errorResponse([], 'خطأ في إرسال الرسالة', 500);
        }

        ChatBotMessage::create([
            'chat_id' => $chat->id,
            'prompt' => $request->prompt,
            'response' => $reply,
        ]);

        $chat?->update(['title' => $request->prompt]);

        return $this->successResponse([
            'chat_id' => $chat->id,
            'prompt' => $request->prompt,
            'response' => $reply,
        ], 'تم إرسال الرسالة بنجاح');
    }
    public function getChats()
    {
        $chats = ChatbotChat::where('user_id', auth()->id())
            ->latest()
            ->get()
            ->map(function ($chat) {
                return [
                    'id'        => $chat->id,
                    'title'      => $chat->title,
                    'date_time' => $chat->created_at->format('Y-m-d H:i'),
                ];
            });

        if ($chats->isEmpty()) {
            return $this->errorResponse([], 'لا توجد محادثات', 404);
        }

        return $this->successResponse($chats, 'تم استرجاع المحادثات بنجاح');
    }

    public function getChatMessages($chatId)
    {
        $chat = ChatbotChat::find($chatId);

        if (!$chat) {
            return $this->errorResponse([], 'المحادثة غير موجودة', 404);
        }

        $messages = ChatBotMessage::where('chat_id', $chatId)
            ->get()
            ->map(function ($message) {
                return [
                    'prompt'     => $message->prompt,
                    'response'   => $message->response,
                    'date_time' => $message->created_at->format('Y-m-d H:i'),
                ];
            });

        if ($messages->isEmpty()) {
            return $this->errorResponse([], 'لا توجد رسائل في هذه المحادثة', 404);
        }

        return $this->successResponse($messages, 'تم استرجاع الرسائل بنجاح');
    }

    public function deleteChat(string $id)
    {
        $chat = ChatbotChat::find($id);
        if ($chat) {
            $chat->delete();
            return $this->successResponse([], 'تم حذف المحادثة بنجاح');
        } else {
            return $this->errorResponse([], 'المحادثة غير موجودة', 404);
        }
    }



}
