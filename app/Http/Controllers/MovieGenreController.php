<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieGenreController extends Controller
{
    /**
     * Attach genre(s) to a movie
     * POST /api/movies/{movie_id}/genres
     */
    public function attach(Request $request, string $movieId)
    {
        $validator = Validator::make($request->all(), [
            'genre_ids' => 'required|array',
            'genre_ids.*' => 'required|integer|exists:genres,genre_id'
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

            // Attach genres (without duplicate if already exists)
            $movie->genres()->syncWithoutDetaching($request->genre_ids);

            // Load genres
            $movie->load('genres');

            return response()->json([
                'success' => true,
                'message' => 'Genres attached to movie successfully',
                'data' => $movie
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to attach genres',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detach genre(s) from a movie
     * DELETE /api/movies/{movie_id}/genres
     */
    public function detach(Request $request, string $movieId)
    {
        $validator = Validator::make($request->all(), [
            'genre_ids' => 'required|array',
            'genre_ids.*' => 'required|integer|exists:genres,genre_id'
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

            // Detach genres
            $movie->genres()->detach($request->genre_ids);

            // Load genres
            $movie->load('genres');

            return response()->json([
                'success' => true,
                'message' => 'Genres detached from movie successfully',
                'data' => $movie
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to detach genres',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync genres for a movie (replace all)
     * PUT /api/movies/{movie_id}/genres
     */
    public function sync(Request $request, string $movieId)
    {
        $validator = Validator::make($request->all(), [
            'genre_ids' => 'required|array',
            'genre_ids.*' => 'required|integer|exists:genres,genre_id'
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

            // Sync genres (replace all with new genres)
            $movie->genres()->sync($request->genre_ids);

            // Load genres
            $movie->load('genres');

            return response()->json([
                'success' => true,
                'message' => 'Movie genres synced successfully',
                'data' => $movie
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync genres',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all genres for a movie
     * GET /api/movies/{movie_id}/genres
     */
    public function index(string $movieId)
    {
        try {
            $movie = Movie::with('genres')->find($movieId);

            if (!$movie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movie not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Movie genres retrieved successfully',
                'data' => [
                    'movie' => $movie->only(['movie_id', 'movie_title']),
                    'genres' => $movie->genres
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve movie genres',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
