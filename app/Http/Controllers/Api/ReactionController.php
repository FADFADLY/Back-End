<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Http\Resources\BookResource;
use App\Http\Resources\EpisodeResource;
use App\Http\Resources\PodcastResource;
use App\Http\Resources\PostResource;
use App\Models\Blog;
use App\Models\Book;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Notifications\NewInteractionNotification;


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
            return $this->successResponse([], 'تم حذف الاعجاب بنجاح', 200);
        }

        Reaction::create([
            'user_id' => auth()->id(),
            'reactable_id' => $validated['id'],
            'reactable_type' => $reactionType,
        ]);

        if (in_array($validated['type'], ['post', 'comment'])) {
            $modelInstance = $reactionType::find($validated['id']);

            if ($modelInstance && $modelInstance->user_id !== auth()->id()) {
                $modelInstance->user->notify(new NewInteractionNotification(
                    'reaction',
                    $modelInstance,
                    auth()->user()->username . ' تفاعل مع ' . ($validated['type'] === 'post' ? 'منشورك' : 'تعليقك'),
                    auth()->id()
                ));
            }
        }

        return $this->successResponse([], 'تم إضافة الاعجاب بنجاح', 201);
    }

    public function likedItems(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:post,comment,book,blog,podcast,episode',
        ]);

        $type = $validated['type'];

        $dbModels = [
            'post' => [Post::class, PostResource::class],
            'blog' => [Blog::class, BlogResource::class],
            'book' => [Book::class, BookResource::class],
        ];

        $typeMap = [
            'post' => Post::class,
            'comment' => Comment::class,
            'blog' => Blog::class,
            'book' => Book::class,
            'podcast' => 'podcast',
            'episode' => 'episode',
        ];

        $reactableType = $typeMap[$type];

        $ids = Reaction::where('user_id', auth()->id())
            ->where('reactable_type', $reactableType)
            ->pluck('reactable_id');

        if (array_key_exists($type, $dbModels)) {
            [$model, $resource] = $dbModels[$type];

            $items = $model::whereIn('id', $ids)->get();

            return $this->successResponse(
                $resource::collection($items),
                'تم جلب العناصر المعمول لها لايك بنجاح'
            );
        }

        $items = [];

        foreach ($ids as $id) {
            $url = $type === 'podcast'
                ? "https://listen-api.listennotes.com/api/v2/podcasts/{$id}"
                : "https://listen-api.listennotes.com/api/v2/episodes/{$id}";

            $response = \Http::withHeaders([
                'X-ListenAPI-Key' => config('services.listennotes.key'),
            ])->get($url);

            if ($response->successful()) {
                $items[] = $response->json();
            }
        }

        $resourceClass = $type === 'podcast'
            ? PodcastResource::class
            : EpisodeResource::class;

        return $this->successResponse(
            $resourceClass::collection(collect($items)),
            'تم جلب العناصر المعمول لها لايك من API بنجاح'
        );
    }



}
