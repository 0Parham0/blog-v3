<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SearchController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// user
Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'getUserInfo']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// blogs
Route::get('/blogs', [BlogController::class, 'getAllBlogs']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/blogs/user', [BlogController::class, 'getUserBlogs']);
    Route::post('/blogs', [BlogController::class, 'createABlog']);
    Route::delete('/blogs/{id}', [BlogController::class, 'deleteABlog']);
    Route::get('/blogs/{id}', [BlogController::class, 'getABlog']);
    Route::patch('/blogs/{id}', [BlogController::class, 'editABlog']);
});

// like
Route::middleware('auth:sanctum')->post('/blogs/{id}/like', [LikeController::class, 'likeOrUnlikeBlog']);

//search
Route::get('/search', [SearchController::class, 'search']);
