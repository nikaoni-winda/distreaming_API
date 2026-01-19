<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/users
     */
    public function index(Request $request)
    {
        try {
            // Pagination
            $perPage = $request->input('per_page', 10);
            $perPage = min(max($perPage, 1), 100);
            $search = $request->input('search');

            $query = User::orderBy('user_nickname', 'asc');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('user_nickname', 'like', "%{$search}%")
                        ->orWhere('user_email', 'like', "%{$search}%");
                });
            }

            $users = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'data' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'total_pages' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total_items' => $users->total(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                    'has_next' => $users->hasMorePages(),
                    'has_prev' => $users->currentPage() > 1
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/users
     */
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'user_nickname' => 'required|string|max:50',
            'user_email' => 'required|email|max:100|unique:users,user_email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'user_nickname' => $request->user_nickname,
                'user_email' => $request->user_email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/users/{id}
     */
    public function show(string $id)
    {
        try {
            $user = User::with(['reviews.movie', 'watchHistory.movie'])->find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Ownership check: Regular users can only view their own profile
            if (!auth()->user()->isAdmin() && $user->user_id != auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden. You can only view your own profile.'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'User retrieved successfully',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/users/{id}
     */
    public function update(Request $request, string $id)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'user_nickname' => 'sometimes|required|string|max:50',
            'user_email' => 'sometimes|required|email|max:100|unique:users,user_email,' . $id . ',user_id',
            'password' => 'sometimes|nullable|string|min:8', // Add password validation
            'plan' => 'sometimes|string|in:mobile,basic,standard,premium'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Ownership check
            if (!auth()->user()->isAdmin() && $user->user_id != auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden. You can only update your own profile.'
                ], 403);
            }

            // Update user data
            $updateData = $request->only(['user_nickname', 'user_email', 'plan']);

            // Hash password if provided
            if ($request->filled('password')) {
                $updateData['password'] = bcrypt($request->password);
            }

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     * DELETE /api/users/{id}
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Ownership check: Regular users can only delete their own account
            if (!auth()->user()->isAdmin() && $user->user_id != auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden. You can only delete your own account.'
                ], 403);
            }

            // Manually delete related records to ensure no foreign key constraints fail
            // (Software-level cascade)
            $user->reviews()->delete();
            $user->watchHistory()->delete();
            $user->tokens()->delete();

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
