<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewInteractionNotification;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index($post_id)
    {
        $comments = Comment::where('post_id', $post_id)->latest()->get()->map(function ($comment) {;
            return [
                'id' => $comment->id,
                'body' => $comment->body,
                'post_id' => $comment->post_id,
                'created_at' => $comment->created_at->diffForHumans(),
                'reactions_count' => $comment->reactions()->count(),
                'reacted' => $comment->reactions()->where('user_id', Auth::id())->exists(),
                'user' => [
                    'user' => $comment->user->username,
                    'image' => $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : null,
                ],
            ];
        });
        if ($comments->count() > 0) {
            return $this->successResponse($comments, 'تم جلب التعليقات بنجاح');
        }
        return $this->errorResponse([],'لا توجد تعليقات', 404);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $post_id)
    {
        try {
            $validated = $request->validate ([
                'body' => ['required', 'string', 'max:255'],
            ], [
                'body.required' => 'المحتوى مطلوب',
            ]);
        }
        catch (\Exception $e) {
            return $this->validationErrorResponse($e,[
                'body',
            ],'خطأ في البيانات المدخلة');
        }

        $post = Post::find($post_id);
        if (!$post) {
            return $this->errorResponse([],'المنشور غير موجود', 404);
        }

        $comment = Comment::create([
            'body'    => $validated['body'],
            'user_id' => auth()->user()->id,
            'post_id' => $post_id
        ]);

        if(!$comment) {
            return $this->errorResponse([],'حدث خطأ اثناء انشاء التعليق', 500);
        }

        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new NewInteractionNotification(
                'comment',
                $post,
                auth()->user()->username . ' علق علي منشورك.',
                auth()->id()
            ));
        }

        return $this->successResponse([], 'تم انشاء التعليق بنجاح', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        $comment->with('reactions.user:id,name');

        if (!$comment) {
            return $this->errorResponse([],'التعليق غير موجود', 404);
        }
        $reactions = $comment->reactions->map(function ($reaction) {
            return [
                'user_name' => $reaction->user->username,
                'user_avatar' => $reaction->user->avatar,
            ];
        });

        return $this->successResponse(
            [
                'reactions' => $reactions,
            ],
            'تم جلب التعليق بنجاح'
        );


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        if (!$comment) {
            return $this->errorResponse([],'التعليق غير موجود', 404);
        }

        if ($comment->user_id !== auth()->id()) {
            return $this->errorResponse([],'ليس لديك صلاحية لتحديث هذا التعليق', 403);
        }

        try {
            $validated = $request->validate ([
                'body' => ['required', 'string', 'max:255'],
            ], [
                'body.required' => 'المحتوى مطلوب',
            ]);
        }
        catch (\Exception $e) {
            return $this->validationErrorResponse($e,[
                'body',
            ],'خطأ في البيانات المدخلة');
        }

        $comment->update([
            'body' => $validated['body'],
        ]);

        return $this->successResponse([], 'تم تحديث التعليق بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (!$comment) {
            return $this->errorResponse([],'التعليق غير موجود', 404);
        }

        if ($comment->user_id !== auth()->id()) {
            return $this->errorResponse([],'ليس لديك صلاحية لحذف هذا التعليق', 403);
        }


        $comment->delete();

        return $this->successResponse([], 'تم حذف التعليق بنجاح');
    }
}
