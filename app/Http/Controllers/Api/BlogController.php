<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\BlogView;
use App\Models\RecommendedBlogs;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BlogController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $recommendedIds = json_decode($user->recommendedBlogs?->recommendations ?? '[]', true);

        $recommendedBlogs = collect();
        if (!empty($recommendedIds)) {
            $recommendedBlogs = Blog::whereIn('id', $recommendedIds)
                ->orderByRaw('FIELD(id, ' . implode(',', $recommendedIds) . ')')
                ->get();
        }

        $otherBlogs = Blog::whereNotIn('id', $recommendedIds)
            ->latest()
            ->get();

        $blogs = $recommendedBlogs->concat($otherBlogs);


        if ($blogs->isEmpty()) {
            return $this->errorResponse([], 'لا توجد مدونات حالياً', 404);
        }

        return $this->successResponse(BlogResource::collection($blogs), 'تم جلب المدونات بنجاح');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
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
            try {
                $response = Http::post('https://ahmedelsherbeny-blog-recommendation.hf.space/recommend', [
                    'query' => $blog->description,
                    'top_k' => 6
                ]);

                if ($response->successful()) {
                    $recommendations = $response->json()['recommendations'];
                    $blogIds = Blog::whereIn('article_id', $recommendations)
                        ->pluck('id')
                        ->toArray();
                    RecommendedBlogs::updateOrCreate(
                        ['user_id' => auth()->user()->id],
                        ['recommendations' => json_encode($blogIds)]
                    );

                }

            } catch (\Exception $e) {
                $this->errorResponse([], 'حدث خطأ أثناء جلب التوصيات', 500);
            }
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


    public function readBlog(string $id)
    {
        $blog = Blog::findOrFail($id);

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
            try {
                $response = Http::post('https://ahmedelsherbeny-blog-recommendation.hf.space/recommend', [
                    'query' => $blog->description,
                    'top_k' => 6
                ]);

                if ($response->successful()) {
                    $recommendations = $response->json()['recommendations'];
                    $blogIds = Blog::whereIn('article_id', $recommendations)
                        ->pluck('id')
                        ->toArray();
                        RecommendedBlogs::updateOrCreate(
                        ['user_id' => auth()->user()->id],
                        ['recommendations' => json_encode($blogIds)]
                    );

                }

            } catch (\Exception $e) {
                $this->errorResponse([], 'حدث خطأ أثناء جلب التوصيات', 500);
            }
        }

        return $this->successResponse([], 'تم جلب المدونة بنجاح');
    }

}
