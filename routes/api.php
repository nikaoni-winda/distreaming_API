<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\WatchHistoryController;
use App\Http\Controllers\MovieGenreController;
use App\Http\Controllers\MovieActorController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Routes are organized into 3 groups:
| 1. Public Routes - No authentication required
| 2. User Protected Routes - Authentication required (for regular users)
| 3. Admin Only Routes - Authentication + Admin role required
*/

// ============================================================================
// PUBLIC ROUTES (No Authentication Required)
// ============================================================================

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Public Read Access - Movies
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{id}', [MovieController::class, 'show']);

// Public Read Access - Genres
Route::get('/genres', [GenreController::class, 'index']);
Route::get('/genres/{id}', [GenreController::class, 'show']);

// Public Read Access - Actors
Route::get('/actors', [ActorController::class, 'index']);
Route::get('/actors/{id}', [ActorController::class, 'show']);

// Public Read Access - Movie Relations
Route::get('/movies/{movie_id}/genres', [MovieGenreController::class, 'index']);
Route::get('/movies/{movie_id}/actors', [MovieActorController::class, 'index']);


// ============================================================================
// USER PROTECTED ROUTES (Authentication Required - Both Admin & User)
// ============================================================================

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Reviews - Users can create, update (own), delete (own)
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews/{id}', [ReviewController::class, 'show']);
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::patch('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

    // User Profile - Users can view and update their own profile
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::patch('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Watch History - Users can manage their own watch history
    Route::apiResource('watch-history', WatchHistoryController::class);
});


// ============================================================================
// ADMIN ONLY ROUTES (Authentication + Admin Role Required)
// ============================================================================

Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    // Movies Management - Admin only
    Route::post('/movies', [MovieController::class, 'store']);
    Route::put('/movies/{id}', [MovieController::class, 'update']);
    Route::patch('/movies/{id}', [MovieController::class, 'update']);
    Route::delete('/movies/{id}', [MovieController::class, 'destroy']);

    // Genres Management - Admin only
    Route::post('/genres', [GenreController::class, 'store']);
    Route::put('/genres/{id}', [GenreController::class, 'update']);
    Route::patch('/genres/{id}', [GenreController::class, 'update']);
    Route::delete('/genres/{id}', [GenreController::class, 'destroy']);

    // Actors Management - Admin only
    Route::post('/actors', [ActorController::class, 'store']);
    Route::put('/actors/{id}', [ActorController::class, 'update']);
    Route::patch('/actors/{id}', [ActorController::class, 'update']);
    Route::delete('/actors/{id}', [ActorController::class, 'destroy']);

    // Movie-Genre Relations - Admin only
    Route::post('/movies/{movie_id}/genres', [MovieGenreController::class, 'attach']);
    Route::put('/movies/{movie_id}/genres', [MovieGenreController::class, 'sync']);
    Route::delete('/movies/{movie_id}/genres', [MovieGenreController::class, 'detach']);

    // Movie-Actor Relations - Admin only
    Route::post('/movies/{movie_id}/actors', [MovieActorController::class, 'attach']);
    Route::put('/movies/{movie_id}/actors', [MovieActorController::class, 'sync']);
    Route::delete('/movies/{movie_id}/actors', [MovieActorController::class, 'detach']);

    // Users List - Admin only (view all users)
    Route::get('/users', [UserController::class, 'index']);
});
