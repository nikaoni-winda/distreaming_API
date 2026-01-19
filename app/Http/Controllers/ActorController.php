<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActorController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/actors
     */
    public function index(Request $request)
    {
        try {
            // Pagination
            $perPage = $request->input('per_page', 10);
            $perPage = min(max($perPage, 1), 100);
            $search = $request->input('search');

            $query = Actor::query();

            if ($search) {
                $query->where('actor_name', 'like', "%{$search}%");
            }

            $actors = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Actors retrieved successfully',
                'data' => $actors->items(),
                'pagination' => [
                    'current_page' => $actors->currentPage(),
                    'total_pages' => $actors->lastPage(),
                    'per_page' => $actors->perPage(),
                    'total_items' => $actors->total(),
                    'from' => $actors->firstItem(),
                    'to' => $actors->lastItem(),
                    'has_next' => $actors->hasMorePages(),
                    'has_prev' => $actors->currentPage() > 1
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve actors',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/actors
     */
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'actor_name' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $actor = Actor::create([
                'actor_name' => $request->actor_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Actor created successfully',
                'data' => $actor
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create actor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/actors/{id}
     */
    public function show(string $id)
    {
        try {
            $actor = Actor::with('movies')->find($id);

            if (!$actor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Actor not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Actor retrieved successfully',
                'data' => $actor
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve actor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/actors/{id}
     */
    public function update(Request $request, string $id)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'actor_name' => 'sometimes|required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $actor = Actor::find($id);

            if (!$actor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Actor not found'
                ], 404);
            }

            $actor->update($request->only(['actor_name']));

            return response()->json([
                'success' => true,
                'message' => 'Actor updated successfully',
                'data' => $actor
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update actor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/actors/{id}
     */
    public function destroy(string $id)
    {
        try {
            $actor = Actor::find($id);

            if (!$actor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Actor not found'
                ], 404);
            }

            $actor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Actor deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete actor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
