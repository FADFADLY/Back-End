<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Models\Book;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReactionController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required',
                'type' => 'required|string|in:post,comment,book,blog,podcast,episode',
            ]);
        } catch (\Exception $e) {
            return $this->validationErrorResponse($e, [
                'id',
                'type',
            ], 'خطأ في البيانات المدخلة');
        }

        $reactionType = match ($validated['type']) {
            'post' => Post::class,
            'comment' => Comment::class,
            'blog' => Blog::class,
            'book' => Book::class,
            'podcast' => 'podcast',   // API ID
            'episode' => 'episode',   // API ID
        };

        $existingReaction = Reaction::where('user_id', auth()->id())
            ->where('reactable_id', $validated['id'])
            ->where('reactable_type', $reactionType)
            ->first();

        if ($existingReaction) {
            $existingReaction->delete();
            return $this->successResponse([], 'تم حذف التفاعل بنجاح', 200);
        }

        Reaction::create([
            'user_id' => auth()->id(),
            'reactable_id' => $validated['id'],
            'reactable_type' => $reactionType,
        ]);

        return $this->successResponse([], 'تم إضافة التفاعل بنجاح', 201);
    }
    public function count(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'type' => 'required|string|in:post,comment,blog,podcast,episode',
        ]);

        $reactionType = match ($validated['type']) {
            'post' => Post::class,
            'comment' => Comment::class,
            'blog' => Blog::class,
            'podcast' => 'podcast',   // API ID
            'episode' => 'episode',   // API ID
        };

        $count = Reaction::where('reactable_id', $validated['id'])
            ->where('reactable_type', $reactionType)
            ->count();

        return $this->successResponse(['count' => $count], 'تم جلب عدد التفاعلات بنجاح');
    }
}
