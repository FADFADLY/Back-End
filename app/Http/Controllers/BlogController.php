<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::latest()->get();
        if(count($blogs) > 0) {
            return ApiResponse::sendResponse(200, 'Blogs Retrieved Successfully', $blogs);
        }
        return ApiResponse::sendResponse(200, 'Blogs are empty', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string'],
            'body' => ['required', 'string'],
        ], [
            'title.required' => 'العنوان مطلوب',
            'body.required' => 'المحتوى مطلوب',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return ApiResponse::sendResponse(200, 'Blog Retrieved Successfully', $blog);
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
