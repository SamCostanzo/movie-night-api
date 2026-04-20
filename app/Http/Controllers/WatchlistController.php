<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function index()
    {
        return response()->json(
            Watchlist::with('user:id,name,username,avatar')
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tmdb_id'     => 'required|integer',
            'title'       => 'required|string',
            'poster_path' => 'nullable|string',
        ]);

        $exists = Watchlist::where('tmdb_id', $validated['tmdb_id'])->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Movie already on watchlist'
            ], 409);
        }

        $item = Watchlist::create([
            'user_id'     => $request->user()->id,
            'tmdb_id'     => $validated['tmdb_id'],
            'title'       => $validated['title'],
            'poster_path' => $validated['poster_path'],
        ]);

        return response()->json($item->load('user:id,name,username,avatar'), 201);
    }

    public function destroy($tmdb_id)
    {
        $item = Watchlist::where('tmdb_id', $tmdb_id)->firstOrFail();
        $item->delete();

        return response()->json([
            'message' => 'Removed from watchlist'
        ]);
    }
}