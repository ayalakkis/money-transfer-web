<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|size:2',
            'user_type' => 'nullable|in:user,agent',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'country' => $request->country,
            'user_type' => $request->user_type ?? 'user',
            'is_approved' => ($request->user_type === 'agent') ? false : true, // Agents need approval
        ]);

        // Assign role using Spatie Permission
        $user->assignRole($user->user_type);

        // Create token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => $user->user_type === 'agent' 
                ? 'Agent account created. Awaiting admin approval.' 
                : 'Account created successfully'
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Account is inactive. Please contact support.'
            ], 403);
        }

        if ($user->isAgent() && !$user->is_approved) {
            return response()->json([
                'success' => false,
                'message' => 'Agent account is pending approval'
            ], 403);
        }

        // Create token with abilities based on role
        $abilities = $this->getAbilitiesForRole($user->user_type);
        $token = $user->createToken('auth-token', $abilities)->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user->load('agent'),
                'token' => $token,
                'role' => $user->user_type,
            ],
            'message' => 'Login successful'
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get current user
     */
    public function me(Request $request)
    {
        $user = $request->user()->load(['agent', 'bankAccounts', 'paymentMethods']);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Register as agent (with business details)
     */
    public function registerAgent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'phone' => 'required|string|max:20',
            // Business details
            'business_name' => 'required|string|max:255',
            'business_type' => 'nullable|string',
            'registration_number' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string|size:2',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'supported_currencies' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'country' => $request->country,
            'user_type' => 'agent',
            'is_approved' => false, // Requires admin approval
        ]);

        $user->assignRole('agent');

        // Create agent profile
        Agent::create([
            'user_id' => $user->id,
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'registration_number' => $request->registration_number,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'supported_currencies' => $request->supported_currencies ?? ['USD'],
            'status' => 'pending',
        ]);

        $token = $user->createToken('auth-token', ['agent:*'])->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user->load('agent'),
                'token' => $token,
            ],
            'message' => 'Agent registration submitted. Awaiting admin approval.'
        ], 201);
    }

    /**
     * Get abilities for role
     */
    private function getAbilitiesForRole($role)
    {
        return match($role) {
            'admin' => ['*'], // All permissions
            'agent' => ['agent:*', 'transaction:read', 'transaction:process'],
            'user' => ['user:*', 'transaction:create', 'transaction:read'],
            default => ['user:*'],
        };
    }
}

