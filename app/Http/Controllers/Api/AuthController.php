<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    /**
     * Register a new user
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'employee_id' => $request->employee_id,
            ]);

            // Assign default role if no employee_id (standalone account)
            if (!$request->employee_id) {
                $user->assignRole('client');
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->success([
                'user' => new UserResource($user->load(['employee', 'roles'])),
                'token' => $token,
                'token_type' => 'Bearer',
            ], 'Registration successful', 201);

        } catch (\Exception $e) {
            return $this->error('Registration failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->unauthorized('Invalid credentials');
        }

        $user = User::where('email', $request->email)->first();

        // Delete old tokens
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => new UserResource($user->load(['employee', 'roles'])),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully');
    }

    /**
     * Get user profile
     */
    public function profile(Request $request)
    {
        $user = $request->user()->load(['employee', 'roles', 'permissions']);

        return $this->success(
            new UserResource($user),
            'Profile retrieved successfully'
        );
    }

    /**
     * Update user profile
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $user = $request->user();
            $user->update($request->validated());

            return $this->success(
                new UserResource($user->load(['employee', 'roles'])),
                'Profile updated successfully'
            );

        } catch (\Exception $e) {
            return $this->error('Profile update failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Change password
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = $request->user();
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            // Delete all tokens to force re-login
            $user->tokens()->delete();

            return $this->success(null, 'Password changed successfully. Please login again.');

        } catch (\Exception $e) {
            return $this->error('Password change failed: ' . $e->getMessage(), 500);
        }
    }
}
