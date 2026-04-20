<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Watched;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $watched_id)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:500',
        ]);

        $watched = Watched::findOrFail($watched_id);

        $review = Review::create([
            'user_id'    => $request->user()->id,
            'watched_id' => $watched->id,
            'body'       => $validated['body'],
        ]);

        return response()->json($review->load('user:id,name,username,avatar'), 201);
    }

    public function destroy(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You can only delete your own reviews'
            ], 403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted'
        ]);
    }
}