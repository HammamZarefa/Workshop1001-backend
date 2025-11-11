<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    // ================================
    // Register
    // ================================
    public function register(Request $request)
    {
      $rules = [
    'first_name'     => 'required|string|max:255',
    'last_name'      => 'required|string|max:255',
    'phone'          => 'required|string|max:20',
    'address'        => 'required|string|max:255',
    'email'          => 'required|email|unique:users,email',
    'password'       => 'required|min:6',
    'firebase_token' => 'nullable|string',
    'fcm_token'      => 'nullable|string',
];

$validator = Validator::make($request->all(), $rules);

if ($validator->fails()) {
    return response()->json([
        'errors' => $validator->errors()
    ], 422);
}

        $user = User::create([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'is_admin'       => false,
            'is_active'      => true,
            'firebase_token' => $request->firebase_token ?? '',
            'fcm_token'      => $request->fcm_token ?? '',
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user'    => new UserResource($user)
        ], 201);
    }

    // ================================
    // Login
    // ================================
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Account is disabled'
            ], 403);
        }

        // Create Sanctum Token
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => new UserResource($user)
        ]);
    }

    // ================================
    // Profile (requires auth:sanctum)
    // ================================
    public function profile(Request $request)
    {
        return new UserResource($request->user());
    }

    // ================================
    // Logout (delete all tokens)
    // ================================
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
