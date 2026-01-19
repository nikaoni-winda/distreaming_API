<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/genres
     */
    public function index(Request $request)
    {
        try {
            // Pagination
            $perPage = $request->input('per_page', 10);
            $perPage = min(max($perPage, 1), 100);
            $search = $request->input('search');

            $query = Genre::query();

            if ($search) {
                $query->where('genre_name', 'like', "%{$search}%");
            }

            $genres = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Genres retrieved successfully',
                'data' => $genres->items(),
                'pagination' => [
                    'current_page' => $genres->currentPage(),
                    'total_pages' => $genres->lastPage(),
                    'per_page' => $genres->perPage(),
                    'total_items' => $genres->total(),
                    'from' => $genres->firstItem(),
                    'to' => $genres->lastItem(),
                    'has_next' => $genres->hasMorePages(),
                    'has_prev' => $genres->currentPage() > 1
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve genres',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/genres
     */
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'genre_name' => 'required|string|max:50|unique:genres,genre_name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $genre = Genre::create([
                'genre_name' => $request->genre_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Genre created successfully',
                'data' => $genre
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create genre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/genres/{id}
     */
    public function show(string $id)
    {
        try {
            $genre = Genre::with('movies')->find($id);

            if (!$genre) {
                return response()->json([
                    'success' => false,
                    'message' => 'Genre not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Genre retrieved successfully',
                'data' => $genre
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve genre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/genres/{id}
     */
    public function update(Request $request, string $id)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'genre_name' => 'sometimes|required|string|max:50|unique:genres,genre_name,' . $id . ',genre_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $genre = Genre::find($id);

            if (!$genre) {
                return response()->json([
                    'success' => false,
                    'message' => 'Genre not found'
                ], 404);
            }

            $genre->update($request->only(['genre_name']));

            return response()->json([
                'success' => true,
                'message' => 'Genre updated successfully',
                'data' => $genre
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update genre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/genres/{id}
     */
    public function destroy(string $id)
    {
        try {
            $genre = Genre::find($id);

            if (!$genre) {
                return response()->json([
                    'success' => false,
                    'message' => 'Genre not found'
                ], 404);
            }

            $genre->delete();

            return response()->json([
                'success' => true,
                'message' => 'Genre deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete genre',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
