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
                    'is_read' => $notification->read_at ? true : false,
                ];
            }),
            'تم جلب الاشعارات بنجاح.'
        );

    }

    public function read(Request $request, $id)
    {
        $notification = $request->user()->notifications()->find($id);

        if (!$notification) {
            return $this->errorResponse([], 'الاشعار غير موجود', 404);
        }

        $notification->markAsRead();

        return $this->successResponse([], 'تم قراءة الاشعار بنجاح.');

    }

}
