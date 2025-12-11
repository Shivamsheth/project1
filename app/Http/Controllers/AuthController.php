<?php

namespace App\Http\Controllers;

use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register Admin
     * Flow: Validation → User Create → Send Verification Email
     */
    public function registerAdmin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'role' => 'required|in:admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $adminExists = User::where('role', 'admin')->exists();
        if ($adminExists) {
            return response()->json([
                'success' => false,
                'message' => 'An admin already exists. Only one admin is allowed.',
            ], 403);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'admin',
        ]);

        // Step 2: Send Verification Email
        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Admin registered successfully. Please verify your email to continue.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified' => false,
            ],
        ], 201);
    }

    /**
     * Register Member
     * Flow: Validation → User Create → Send Verification Email
     */
    public function registerMember(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'role' => 'required|in:member',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (User::where('email', $request->input('email'))->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A user with this email already exists.',
            ], 409);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'member',
        ]);

        // Step 2: Send Verification Email
        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Member registered successfully. Please verify your email to continue.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified' => false,
            ],
        ], 201);
    }

    /**
     * Login
     * Step 3: Check if email is verified before login
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        // STEP 3: Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email address before logging in.',
                'data' => [
                    'email' => $user->email,
                    'email_verified' => false,
                ],
            ], 403);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified' => true,
                'token' => $token,
            ],
        ], 200);
    }

    /**
     * Logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful.',
        ], 200);
    }

    /**
     * Get Profile
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified' => $user->hasVerifiedEmail(),
                'created_at' => $user->created_at,
            ],
        ], 200);
    }

    /**
     * Verify Email with OTP
     * Flow: Validate OTP → Mark as verified → Send Welcome Email via Queue
     */
    public function verifyEmail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'Email already verified.',
                'data' => [
                    'email_verified' => true,
                ],
            ], 200);
        }

        // Check if OTP exists and is not expired
        if (!$user->otp || now()->isAfter($user->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired or is invalid. Please request a new one.',
            ], 400);
        }

        // Verify OTP
        if ($user->otp !== $request->input('otp')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.',
            ], 400);
        }

        // Mark email as verified
        if ($user->markEmailAsVerified()) {
            // Clear OTP after successful verification
            $user->update([
                'otp' => null,
                'otp_expires_at' => null,
            ]);

            // Send Welcome Email using Queue (Redis)
            SendWelcomeEmail::dispatch($user);

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully. Welcome email has been sent. You can now login.',
                'data' => [
                    'email_verified' => true,
                ],
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to verify email.',
        ], 500);
    }

    /**
     * Resend Verification Email
     */
    public function resendVerificationEmail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email is already verified.',
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Verification email sent successfully.',
        ], 200);
    }
}
