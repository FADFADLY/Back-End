<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Models\Post;
use App\Models\PollVote;
use App\Models\Reaction;
use App\Models\PollOption;
use App\Traits\ApiResponse;
use App\Models\PostLocation;
use Illuminate\Http\Request;
use App\Enums\AttachmentTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Services\PostAnalysisService;

class PostController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user:id,username')
            ->latest()
            ->get();

        if ($posts->isEmpty()) {
            return $this->errorResponse([], 'لا توجد منشورات', 404);
        }


        return $this->successResponse(PostResource::collection($posts), 'تم جلب المنشورات بنجاح');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, PostAnalysisService $analyzer)
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string',
                'attachment' => 'nullable',
                'type' => 'nullable|string',
            ], [
                'content.required' => 'المحتوى مطلوب',
            ]);
        } catch (\Exception $e) {
            return $this->validationErrorResponse($e, [
                'content',
                'attachment',
                'type'
            ], 'خطأ في البيانات المدخلة');
        }

        $result = $analyzer->analyze($validated['content']);

        if (!$result['success']) {
            return $this->errorResponse([], $result['message'], 403);
        }

        $typeEnum = AttachmentTypeEnum::fromLabel($validated['type'] ?? 'text');

        $post = Post::create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'type' => $typeEnum->value,
        ]);

        if (!$post) {
            return $this->errorResponse([], 'حدث خطأ اثناء انشاء المنشور', 500);
        }

        if ($typeEnum === AttachmentTypeEnum::POLL) {
            $options = json_decode($validated['attachment'], true);
            if (is_array($options)) {
                foreach ($options as $option) {
                    PollOption::create([
                        'post_id' => $post->id,
                        'option' => $option,
                    ]);
                }
            }
            $post->update(['attachment' => $post->content]);
        }

        if ($typeEnum === AttachmentTypeEnum::LOCATION) {
            $locationData = json_decode($validated['attachment'], true);
            if (is_array($locationData) && isset($locationData['latitude'], $locationData['longitude'])) {
                PostLocation::create([
                    'post_id' => $post->id,
                    'latitude' => $locationData['latitude'],
                    'longitude' => $locationData['longitude'],
                    'label' => $locationData['label'] ?? null,
                ]);
                $post->update(['attachment' => $locationData['label']]);
            }
        }

        if ($typeEnum === AttachmentTypeEnum::ARTICLE) {
            if (!empty($validated['attachment'])) {
                $post->update(['attachment' => $validated['attachment']]);

                $blog = Blog::findOrFail((int)$validated['attachment']);
                $blog->increment('share_count');

            }

        }



        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $post->update(['attachment' => $path]);
        }

        return $this->successResponse([], 'تم انشاء المنشور بنجاح', 201);
    }

    public function show(Post $post)
    {
        $post->load(['reactions.user:id,username,avatar', 'pollOptions.pollVotes.user:id,username']);

        if (!$post) {
            return $this->errorResponse([], 'المنشور غير موجود', 404);
        }

        $reactions = $post->reactions->map(function ($reaction) {
            return [
                'user_name' => $reaction->user->username,
                'user_avatar' => $reaction->user->avatar ? asset('storage/' . $reaction->user->avatar) : null,
            ];
        });

        return $this->successResponse(
            [
                'reactions' => $reactions,
            ],
            'تم جلب المنشور بنجاح',
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post, PostAnalysisService $analyzer)
    {
        if (!$post) {
            return $this->errorResponse([], 'حدث خطأ اثناء تحديث المنشور', 500);
        }

        if ($post->user_id !== auth()->id()) {
            return $this->errorResponse([], 'ليس لديك صلاحية لتحديث هذا المنشور', 403);
        }

        try {
            $validated = $request->validate([
                'content' => 'required|string',
            ], [
                'content.required' => 'المحتوى مطلوب',
            ]);
        } catch (\Exception $e) {
            return $this->validationErrorResponse($e, [
                'content',
            ], 'فشل في تحديث المنشور');
        }

        $result = $analyzer->analyze($validated['content']);

        if (!$result['success']) {
            return $this->errorResponse([], $result['message'], 403);
        }


        $post->update([
            'content'  => $validated['content'],
        ]);

        return $this->successResponse([], 'تم تحديث المنشور بنجاح', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (!$post) {
            return $this->errorResponse([], 'المنشور غير موجود', 404);
        }

        if ($post->user_id !== auth()->id()) {
            return $this->errorResponse([], 'ليس لديك صلاحية لحذف هذا المنشور', 403);
        }

        $post->delete();

        return $this->successResponse([], 'تم حذف المنشور بنجاح', 200);
    }

    public function vote(Request $request, Post $post)
    {
        $validated = $request->validate([
            'option_id' => 'required|exists:poll_options,id',
        ]);

        $option = PollOption::findOrFail($validated['option_id']);

        if ($option->post_id !== $post->id) {
            return $this->errorResponse([], 'الخيار لا ينتمي لهذا البوست', 403);
        }

        $userId = auth()->id();

        $existingVote = PollVote::where('user_id', $userId)
            ->whereIn('poll_option_id', $post->pollOptions->pluck('id'))
            ->first();

        if ($existingVote) {
            if ($existingVote->poll_option_id == $option->id) {
                $option->decrement('votes');
                $existingVote->delete();

                return $this->successResponse([], 'تم إلغاء تصويتك بنجاح');
            } else {
                PollOption::where('id', $existingVote->poll_option_id)->decrement('votes');
                $option->increment('votes');

                $existingVote->update([
                    'poll_option_id' => $option->id,
                ]);

                return $this->successResponse([], 'تم تعديل تصويتك بنجاح');
            }
        }

        $option->increment('votes');

        PollVote::create([
            'user_id' => $userId,
            'poll_option_id' => $option->id,
        ]);

        return $this->successResponse([], 'تم تسجيل تصويتك بنجاح');
    }

}
