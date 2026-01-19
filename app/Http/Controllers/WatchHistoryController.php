<?php

namespace App\Http\Controllers;

use App\Models\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WatchHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/watch-history
     */
    public function index(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }

            // Pagination
            $perPage = $request->input('per_page', 10);
            $perPage = min(max($perPage, 1), 100);

            // Get watch history for authenticated user only
            $watchHistory = WatchHistory::where('user_id', auth()->id())
                ->with(['movie:movie_id,movie_title,movie_poster,movie_duration,production_year,average_rating', 'movie.genres:genre_id,genre_name'])
                ->orderBy('watch_date', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Watch history retrieved successfully',
                'data' => $watchHistory->items(),
                'pagination' => [
                    'current_page' => $watchHistory->currentPage(),
                    'total_pages' => $watchHistory->lastPage(),
                    'per_page' => $watchHistory->perPage(),
                    'total_items' => $watchHistory->total(),
                    'from' => $watchHistory->firstItem(),
                    'to' => $watchHistory->lastItem(),
                    'has_next' => $watchHistory->hasMorePages(),
                    'has_prev' => $watchHistory->currentPage() > 1
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve watch history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/watch-history
     */
    public function store(Request $request)
    {
        // Validate input (watch_date is auto-filled, no need to input)
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,user_id',
            'movie_id' => 'required|integer|exists:movies,movie_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $watchHistory = new WatchHistory();
            $watchHistory->user_id = $request->user_id;
            $watchHistory->movie_id = $request->movie_id;
            $watchHistory->watch_date = now()->toDateString(); // Auto-set to today
            $watchHistory->save();

            // Load relationships
            $watchHistory->load(['user', 'movie']);

            return response()->json([
                'success' => true,
                'message' => 'Watch history created successfully',
                'data' => $watchHistory
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create watch history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/watch-history/{id}
     */
    public function show(string $id)
    {
        try {
            $watchHistory = WatchHistory::with(['user', 'movie'])->find($id);

            if (!$watchHistory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Watch history not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Watch history retrieved successfully',
                'data' => $watchHistory
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve watch history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/watch-history/{id}
     */
    public function update(Request $request, string $id)
    {
        // Validate input (watch_date cannot be changed)
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|integer|exists:users,user_id',
            'movie_id' => 'sometimes|required|integer|exists:movies,movie_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $watchHistory = WatchHistory::find($id);

            if (!$watchHistory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Watch history not found'
                ], 404);
            }

            $watchHistory->update($request->only(['user_id', 'movie_id']));

            // Load relationships
            $watchHistory->load(['user', 'movie']);

            return response()->json([
                'success' => true,
                'message' => 'Watch history updated successfully',
                'data' => $watchHistory
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update watch history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/watch-history/{id}
     */
    public function destroy(string $id)
    {
        try {
            $watchHistory = WatchHistory::find($id);

            if (!$watchHistory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Watch history not found'
                ], 404);
            }

            $watchHistory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Watch history deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete watch history',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}