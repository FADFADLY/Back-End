<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->get();

        if ($notifications->isEmpty()) {
            return $this->errorResponse([], 'لا توجد إشعارات', 404);
        }

        return $this->successResponse(
            $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? 'general',
                    'message' => $notification->data['message'] ?? 'No message',
                    'sender' => [
                        'username' => User::find($notification->data['sender_id'])->username ?? 'Unknown',
                        'image' => User::find($notification->data['sender_id'])->avatar
                            ? asset('storage/' . User::find($notification->data['sender_id'])->avatar)
                            : null,
                    ],
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            }),
            'تم جلب الاشعارات بنجاح.'
        );

    }

    public function unread(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->unreadNotifications()->latest()->get(),
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->find($id);

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
        }

        $notification->markAsRead();

        return response()->json(['success' => true, 'message' => 'Notification marked as read.']);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
    }
}
