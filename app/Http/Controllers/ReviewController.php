<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/reviews
     */
    public function index(Request $request)
    {
        try {
            // Pagination
            $perPage = $request->input('per_page', 10);
            $perPage = min(max($perPage, 1), 100);

            $reviews = Review::with(['user', 'movie'])->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Reviews retrieved successfully',
                'data' => $reviews->items(),
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'total_pages' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total_items' => $reviews->total(),
                    'from' => $reviews->firstItem(),
                    'to' => $reviews->lastItem(),
                    'has_next' => $reviews->hasMorePages(),
                    'has_prev' => $reviews->currentPage() > 1
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/reviews
     */
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,user_id',
            'movie_id' => 'required|integer|exists:movies,movie_id',
            'rating' => 'required|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ownership check: Regular users can only create reviews for themselves
        if (!auth()->user()->isAdmin() && $request->user_id != auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. You can only create reviews for yourself.'
            ], 403);
        }

        try {
            // Check if user already reviewed this movie
            $existingReview = Review::where('user_id', $request->user_id)
                ->where('movie_id', $request->movie_id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this movie'
                ], 409); // 409 Conflict
            }

            $review = new Review();
            $review->user_id = $request->user_id;
            $review->movie_id = $request->movie_id;
            $review->rating = $request->rating;
            $review->review_date = now()->toDateString(); // Auto-set to today
            $review->save();

            // Update average rating for this movie
            $movie = Movie::find($request->movie_id);
            if ($movie) {
                $movie->updateAverageRating();
            }

            // Return ONLY the review data, NO relationships
            return response()->json([
                'success' => true,
                'message' => 'Review created successfully',
                'data' => $review->fresh() // Fresh data tanpa relations
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/reviews/{id}
     */
    public function show(string $id)
    {
        try {
            $review = Review::with(['user', 'movie'])->find($id);

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Review retrieved successfully',
                'data' => $review
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/reviews/{id}
     */
    public function update(Request $request, string $id)
    {
        // Validate input (only rating can be updated)
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $review = Review::find($id);

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found'
                ], 404);
            }

            // Ownership check: Regular users can only update their own reviews
            if (!auth()->user()->isAdmin() && $review->user_id != auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden. You can only update your own reviews.'
                ], 403);
            }

            // Update only rating (user_id and movie_id cannot be updated)
            $review->update(['rating' => $request->rating]);

            // Update average rating for this movie
            $movie = Movie::find($review->movie_id);
            if ($movie) {
                $movie->updateAverageRating();
            }

            // Load relationships
            $review->load(['user', 'movie']);

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => $review
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/reviews/{id}
     */
    public function destroy(string $id)
    {
        try {
            $review = Review::find($id);

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found'
                ], 404);
            }

            // Ownership check: Regular users can only delete their own reviews
            if (!auth()->user()->isAdmin() && $review->user_id != auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden. You can only delete your own reviews.'
                ], 403);
            }

            // Save movie_id before delete
            $movieId = $review->movie_id;

            $review->delete();

            // Update average rating for this movie
            $movie = Movie::find($movieId);
            if ($movie) {
                $movie->updateAverageRating();
            }

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
