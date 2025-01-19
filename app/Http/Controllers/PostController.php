<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->get();
        if (count($posts) > 0) {
            return ApiResponse::sendResponse(200, 'Posts Retrieved Successfully', $posts);
        }
        return ApiResponse::sendResponse(200, 'Posts are empty', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        dd(auth()->user());
        $validator = Validator::make($request->all(), [
            'body' => ['required', 'string', 'max:255'],
        ], [
            'body.required' => 'المحتوى مطلوب',
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, 'Post Creation Errors', $validator->errors());
        }

        $post = Post::create([
            'body'  => $request->body,
            'user_id' => auth()->user()->id,
        ]);

        return ApiResponse::sendResponse(200, 'Post created successfully', $post);

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
