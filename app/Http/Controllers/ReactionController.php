<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    // Get all reactions (Optional: Filter by reactable type and ID)
    public function index(Request $request)
    {

    }

    // Store a new reaction
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'reactable_id' => 'required|integer',
            'reactable_type' => 'required|string',
        ]);

        // Toggle reaction (add or remove)
        $existingReaction = Reaction::where('user_id', $validated['user_id'])
            ->where('reactable_id', $validated['reactable_id'])
            ->where('reactable_type', $validated['reactable_type'])
            ->first();

        if ($existingReaction) {
            $existingReaction->delete();
            return ApiResponse::sendResponse(200, 'Reaction removed successfully', null);
        }

        $reaction = Reaction::create($validated);

        return ApiResponse::sendResponse(201, 'Reaction added successfully', $reaction);
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
