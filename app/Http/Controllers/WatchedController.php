<?php

namespace App\Http\Controllers;

use App\Models\Watched;
use App\Models\Watchlist;
use Illuminate\Http\Request;

class WatchedController extends Controller
{
    public function index()
    {
        return response()->json(
            Watched::with(['user:id,name,username,avatar', 'reviews.user:id,name,username,avatar'])
                ->orderBy('watched_date', 'desc')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tmdb_id'      => 'required|integer',
            'title'        => 'required|string',
            'poster_path'  => 'nullable|string',
            'watched_date' => 'required|date',
        ]);

        $exists = Watched::where('tmdb_id', $validated['tmdb_id'])->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Movie already marked as watched'
            ], 409);
        }

        $item = Watched::create([
            'user_id'      => $request->user()->id,
            'tmdb_id'      => $validated['tmdb_id'],
            'title'        => $validated['title'],
            'poster_path'  => $validated['poster_path'],
            'watched_date' => $validated['watched_date'],
        ]);

        // Remove from watchlist if it was there
        Watchlist::where('tmdb_id', $validated['tmdb_id'])->delete();

        return response()->json($item->load('user:id,name,username,avatar'), 201);
    }

    public function destroy($tmdb_id)
    {
        $item = Watched::where('tmdb_id', $tmdb_id)->firstOrFail();
        $item->delete();

        return response()->json([
            'message' => 'Removed from watched'
        ]);
    }
}