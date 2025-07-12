<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Models\BlogView;
use App\Models\Reaction;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use Illuminate\Support\Facades\Auth;
use App\Services\BlogRecommendationService;

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
}
