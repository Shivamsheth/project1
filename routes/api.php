<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/auth/register-admin', [AuthController::class, 'registerAdmin']);
Route::post('/auth/register-member', [AuthController::class, 'registerMember']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Email verification routes (public, no auth required)
Route::post('/auth/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('/auth/resend-verification-email', [AuthController::class, 'resendVerificationEmail']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);

    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
});
