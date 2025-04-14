<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    use ApiResponse;


    // Store a new reaction
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:post,comment',
        ],[
            'id.required' => 'reactable_id is required',
            'type.required' => 'reactable_type is required',
            'type.in' => 'reactable_type must be either post or comment',
        ]);

        $reactionType = null;
        if ($validated['type'] === 'post') {
            $reactionType = Post::class;
        } elseif ($validated['type'] === 'comment') {
            $reactionType = Comment::class;
        }
        $existingReaction = Reaction::where('user_id', auth()->user()->id)
            ->where('reactable_id', $validated['id'])
            ->where('reactable_type', $reactionType)
            ->first();

        if ($existingReaction) {
            $existingReaction->delete();
            return $this->successResponse(
                null,
                'تم حذف التفاعل  بنجاح',
                200
            );
        }

        Reaction::create([
            'user_id' => auth()->user()->id,
            'reactable_id' => $validated['id'],
            'reactable_type' => $reactionType,
        ]);

        return $this->successResponse(
            null,
            'تم اضافة التفاعل  بنجاح',
            201
        );

    }

    // Show a specific reaction
    public function show($id)
    {

    }

    // Update a reaction
    public function update(Request $request, $id)
    {

    }

    // Delete a reaction
    public function destroy($id)
    {

    }
}
