<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($post_id)
    {
//        $post = Post::find($post_id);
//        if (!$post) {
//            return ApiResponse::sendResponse(404, 'Post not found');
//        }

        // Get comments for the post
//        $comments = $post->comments()->latest()->get();
        $comments = Comment::where('post_id', $post_id)->latest()->get();
        if ($comments->count() > 0) {
            return ApiResponse::sendResponse(200, 'Comments Retrieved Successfully', $comments);
        }

        return ApiResponse::sendResponse(200, 'No comments found for this post', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $post_id)
    {
        $validator = Validator::make($request->all(), [
            'body' => ['required', 'string', 'max:255'],
        ], [
            'body.required' => 'المحتوى مطلوب',
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, 'Comment Creation Errors', $validator->errors());
        }

        $post = Post::find($post_id);
        if (!$post) {
            return ApiResponse::sendResponse(404, 'Post not found');
        }

        $comment = Comment::create([
            'body'    => $request->body,
            'user_id' => auth()->user()->id,
            'post_id' => $post_id
        ]);

        return ApiResponse::sendResponse(200, 'Comment created successfully', $comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        // Load the reactions with user details
        $comment->load('reactions.user:id,name');

        // Transform the reactions to return only user details
        $reactions = $comment->reactions->map(function ($reaction) {
            return $reaction->user;
        });

        return ApiResponse::sendResponse(200, 'Comment details fetched successfully', [
            'comment' => $comment->body,
            'reactions' => $reactions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
