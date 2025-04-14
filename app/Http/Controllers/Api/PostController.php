<?php

namespace App\Http\Controllers\Api;

use App\Enums\AttachmentTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\PollOption;
use App\Models\Post;
use App\Models\PostLocation;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

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
            return $this->errorResponse('لا توجد منشورات', 404);
        }


        return $this->successResponse(PostResource::collection($posts), 'تم جلب المنشورات بنجاح');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'attachment' => 'nullable',
            'type' => 'nullable|string',
        ], [
            'content.required' => 'المحتوى مطلوب',
        ]);

        $typeEnum = AttachmentTypeEnum::fromLabel($validated['type'] ?? 'text');

        // إنشاء البوست
        $post = Post::create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'type' => $typeEnum->value,
            'attachment' => $validated['attachment'] ,
        ]);

        if (!$post) {
            return $this->errorResponse('حدث خطأ اثناء انشاء المنشور', 500);
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

        if ($request->hasFile('attachment')) {

            $path = $request->file('attachment')->store('attachments' ,'public');
            $post->update(['attachment' => $path]);
        }

        return $this->successResponse(null, 'تم إنشاء المنشور بنجاح', 201);
    }

    public function show(Post $post)
    {

        $post->with('reactions.user:id,name');

        if (!$post) {
            return $this->errorResponse('المنشور غير موجود', 404);
        }

        $reactions = $post->reactions->map(function ($reaction) {
            return [
                'user_name' => $reaction->user->username,
                'user_avatar' => $reaction->user->avatar,
            ];
        });
        return $this->successResponse(
           [
                'reactions' => $reactions,
           ] ,
            'تم جلب المنشور بنجاح',
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ], [
            'content.required' => 'المحتوى مطلوب',
        ]);

        if(!$post) {
            return $this->errorResponse('حدث خطأ اثناء تحديث المنشور', 500);
        }

        $post->update([
            'content'  => $validated['content'],
        ]);

        return $this->successResponse(null, 'تم تحديث المنشور بنجاح', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (!$post) {
            return $this->errorResponse('المنشور غير موجود', 404);
        }

        $post->delete();

        return $this->successResponse(null, 'تم حذف المنشور بنجاح', 200);
    }
}
