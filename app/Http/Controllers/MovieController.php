<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/movies
     * GET /api/movies?search=love
     * GET /api/movies?category_id=1
     * GET /api/movies?search=love&category_id=1
     * GET /api/movies?sort_by=rating&order=desc
     * GET /api/movies?search=love&category_id=1&sort_by=rating&order=desc
     */
    public function index(Request $request)
    {
        try {
            $query = Movie::query();

            // Search by movie title
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where('movie_title', 'LIKE', '%' . $search . '%');
            }

            // Filter by genre
            if ($request->has('genre_id')) {
                $genreId = $request->input('genre_id');
                $query->whereHas('genres', function ($q) use ($genreId) {
                    $q->where('genres.genre_id', $genreId);
                });
            }

            // Sorting
            $sortBy = $request->input('sort_by', 'movie_id'); // Default: movie_id
            $order = $request->input('order', 'asc'); // Default: asc

            // Validate sort_by column
            $allowedSortColumns = ['movie_id', 'movie_title', 'rating', 'year', 'duration'];

            // Map user-friendly names to actual column names
            $columnMap = [
                'rating' => 'average_rating',
                'year' => 'production_year',
                'duration' => 'movie_duration',
                'title' => 'movie_title'
            ];

            // Get actual column name
            $actualColumn = $columnMap[$sortBy] ?? $sortBy;

            // Validate order
            $order = in_array(strtolower($order), ['asc', 'desc']) ? strtolower($order) : 'asc';

            // Apply sorting
            $query->orderBy($actualColumn, $order);

            // Pagination
            $perPage = $request->input('per_page', 10); // Default 10 items per page
            $perPage = min(max($perPage, 1), 100); // Between 1-100

            // Get paginated results with genres and actors
            $movies = $query->with(['genres', 'actors'])->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Movies retrieved successfully',
                'data' => $movies->items(),
                'pagination' => [
                    'current_page' => $movies->currentPage(),
                    'total_pages' => $movies->lastPage(),
                    'per_page' => $movies->perPage(),
                    'total_items' => $movies->total(),
                    'from' => $movies->firstItem(),
                    'to' => $movies->lastItem(),
                    'has_next' => $movies->hasMorePages(),
                    'has_prev' => $movies->currentPage() > 1
                ],
                'filters' => [
                    'search' => $request->input('search'),
                    'genre_id' => $request->input('genre_id'),
                    'sort_by' => $sortBy,
                    'order' => $order
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve movies',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/movies
     */
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'movie_title' => 'required|string|max:150',
            'movie_duration' => 'required|integer|min:1',
            'production_year' => 'required|integer|min:1888|max:' . (date('Y') + 5),
            'movie_poster' => 'nullable|string|max:500',
            'movie_description_en' => 'nullable|string',
            'movie_description_id' => 'nullable|string',
            'trailer_url' => 'nullable|string|url|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $movie = Movie::create([
                'movie_title' => $request->movie_title,
                'movie_duration' => $request->movie_duration,
                'production_year' => $request->production_year,
                'movie_poster' => $request->movie_poster,
                'movie_description_en' => $request->movie_description_en,
                'movie_description_id' => $request->movie_description_id,
                'trailer_url' => $request->trailer_url
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Movie created successfully',
                'data' => $movie
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create movie',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/movies/{id}
     */
    public function show(string $id)
    {
        try {
            $movie = Movie::with(['genres', 'actors'])->find($id);

            if (!$movie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movie not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Movie retrieved successfully',
                'data' => $movie
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve movie',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/movies/{id}
     */
    public function update(Request $request, string $id)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'movie_title' => 'sometimes|required|string|max:150',
            'movie_duration' => 'sometimes|required|integer|min:1',
            'production_year' => 'sometimes|required|integer|min:1888|max:' . (date('Y') + 5),
            'movie_poster' => 'sometimes|nullable|string|max:500',
            'movie_description_en' => 'sometimes|nullable|string',
            'movie_description_id' => 'sometimes|nullable|string',
            'trailer_url' => 'sometimes|nullable|string|url|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $movie = Movie::find($id);

            if (!$movie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movie not found'
                ], 404);
            }

            $movie->update($request->only([
                'movie_title',
                'movie_duration',
                'production_year',
                'movie_poster',
                'movie_description_en',
                'movie_description_id',
                'trailer_url'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Movie updated successfully',
                'data' => $movie
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update movie',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/movies/{id}
     */
    public function destroy(string $id)
    {
        try {
            $movie = Movie::find($id);

            if (!$movie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movie not found'
                ], 404);
            }

            // Detach related records first to avoid foreign key constraint errors
            $movie->genres()->detach();
            $movie->actors()->detach();

            // Delete related reviews and watch history
            $movie->reviews()->delete();
            $movie->watchHistory()->delete();

            // Now delete the movie
            $movie->delete();

            return response()->json([
                'success' => true,
                'message' => 'Movie deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete movie',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
