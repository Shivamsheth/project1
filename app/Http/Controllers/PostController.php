<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
   
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Both admin and members can see all posts from all users
        $posts = Post::with('user:id,name,email,role')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Posts retrieved successfully.',
            'data' => $posts,
        ], 200);
    }

   
    public function show(Request $request, Post $post): JsonResponse
    {
        // All authenticated users can view all posts (both admin and members)
        $post->load('user:id,name,email,role');

        return response()->json([
            'success' => true,
            'message' => 'Post retrieved successfully.',
            'data' => $post,
        ], 200);
    }

    
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:150',
            'content' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        $post = Post::create([
            'user_id' => $user->id,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        $post->load('user:id,name,email,role');

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully.',
            'data' => $post,
        ], 201);
    }

    
    public function update(Request $request, Post $post): JsonResponse
    {
        $user = $request->user();

        // Admin can update any post, members can only update their own
        if (!$user->isAdmin() && $post->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You cannot update this post.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:150',
            'content' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $post->update($request->only('title', 'content'));
        $post->load('user:id,name,email,role');

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully.',
            'data' => $post,
        ], 200);
    }

    
    public function destroy(Request $request, Post $post): JsonResponse
    {
        $user = $request->user();

        // Admin can delete any post, members can only delete their own
        if (!$user->isAdmin() && $post->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You cannot delete this post.',
            ], 403);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully.',
        ], 200);
    }
}
