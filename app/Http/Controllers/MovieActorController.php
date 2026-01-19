<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieActorController extends Controller
{
    /**
     * Attach actor(s) to a movie
     * POST /api/movies/{movie_id}/actors
     */
    public function attach(Request $request, string $movieId)
    {
        $validator = Validator::make($request->all(), [
            'actor_ids' => 'required|array',
            'actor_ids.*' => 'required|integer|exists:actors,actor_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $movie = Movie::find($movieId);

            if (!$movie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movie not found'
                ], 404);
            }

            // Attach actors (without duplicate if already exists)
            $movie->actors()->syncWithoutDetaching($request->actor_ids);

            // Load actors
            $movie->load('actors');

            return response()->json([
                'success' => true,
                'message' => 'Actors attached to movie successfully',
                'data' => $movie
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to attach actors',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detach actor(s) from a movie
     * DELETE /api/movies/{movie_id}/actors
     */
    public function detach(Request $request, string $movieId)
    {
        $validator = Validator::make($request->all(), [
            'actor_ids' => 'required|array',
            'actor_ids.*' => 'required|integer|exists:actors,actor_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $movie = Movie::find($movieId);

            if (!$movie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movie not found'
                ], 404);
            }

            // Detach actors
            $movie->actors()->detach($request->actor_ids);

            // Load actors
            $movie->load('actors');

            return response()->json([
                'success' => true,
                'message' => 'Actors detached from movie successfully',
                'data' => $movie
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to detach actors',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync actors for a movie (replace all)
     * PUT /api/movies/{movie_id}/actors
     */
    public function sync(Request $request, string $movieId)
    {
        $validator = Validator::make($request->all(), [
            'actor_ids' => 'required|array',
            'actor_ids.*' => 'required|integer|exists:actors,actor_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $movie = Movie::find($movieId);

            if (!$movie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movie not found'
                ], 404);
            }

            // Sync actors (replace all with new actors)
            $movie->actors()->sync($request->actor_ids);

            // Load actors
            $movie->load('actors');

            return response()->json([
                'success' => true,
                'message' => 'Movie actors synced successfully',
                'data' => $movie
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync actors',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all actors for a movie
     * GET /api/movies/{movie_id}/actors
     */
    public function index(string $movieId)
    {
        try {
            $movie = Movie::with('actors')->find($movieId);

            if (!$movie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movie not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Movie actors retrieved successfully',
                'data' => [
                    'movie' => $movie->only(['movie_id', 'movie_title']),
                    'actors' => $movie->actors
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve movie actors',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
