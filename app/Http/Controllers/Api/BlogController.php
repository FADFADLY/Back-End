<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::latest()->get();

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
