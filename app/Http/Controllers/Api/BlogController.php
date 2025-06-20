<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\BlogView;
use App\Services\BlogRecommendationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(BlogRecommendationService $recommendationService)
    {
        $blogs = $recommendationService->getOrderedBlogs();


        if ($blogs->isEmpty()) {
            return $this->errorResponse([], 'لا توجد مدونات حالياً', 404);
        }

        return $this->successResponse(BlogResource::collection($blogs), 'تم جلب المدونات بنجاح');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|url',
            'author' => 'nullable|string',
            'description' => 'nullable|string',
            'publish_date' => 'nullable|date',
        ]);

        $blog = Blog::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'image' => $validated['image'] ?? null,
            'author' => $validated['author'] ?? auth()->user()->name,
            'description' => $validated['description'] ?? null,
            'publish_date' => $validated['publish_date'] ?? now(),
            'views_count' => 0,
            'likes_count' => 0,
            'share_count' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء البلوج بنجاح.',
            'data' => $blog,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Blog $blog, BlogRecommendationService $recommendationService)
    {
        if (!$blog) {
            return $this->errorResponse([], 'المدونة غير موجودة', 404);
        }

        $user = Auth::user();

        if ($user) {
            $alreadyViewed = BlogView::where('user_id', $user->id)
                ->where('blog_id', $blog->id)
                ->exists();

            if (!$alreadyViewed) {
                $blog->increment('views_count');

                BlogView::create([
                    'user_id' => $user->id,
                    'blog_id' => $blog->id,
                ]);
            }
        }

        if ($blog->description) {
            $recommendationService->generateRecommendations($blog->description);
        }

        return $this->successResponse(new BlogResource($blog), 'تم جلب المدونة بنجاح');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        //
    }

}
