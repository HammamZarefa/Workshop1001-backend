<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'],
            'address' => $data['address'],
        ]);

        $tokenName = $request->device_type ?? 'auth_token';
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            'token_type' => $tokenName,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $tokenName = $request->device_type ?? 'auth_token';
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            'token_type' => $tokenName,
        ]);
    }

    public function user(Request $request)
    {
        return new UserResource($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
//
//    public function forgotPassword(Request $request)
//    {
//        $request->validate(['email' => 'required|email']);
//
//        $user = User::where('email', $request->email)->first();
//
//
//        if (!$user) {
//            return response()->json([
//                'message' => 'No user found with this email.'
//            ], 404);
//        }
//
//        // إرسال رابط إعادة التعيين
//        $status = Password::sendResetLink(['email' => $request->email]);
//
//        if ($status === Password::RESET_LINK_SENT) {
//            return response()->json(['message' => __($status)]);
//        }
//
//        return response()->json([
//            'message' => __($status),
//        ], 422);
//    }
//
//
//    public function resetPassword(Request $request)
//    {
//        $request->validate([
//            'token' => 'required',
//            'email' => 'required|email',
//            'password' => 'required|min:8|confirmed',
//        ]);
//
//        $status = Password::reset(
//            $request->only('email', 'password', 'password_confirmation', 'token'),
//            function (User $user, string $password) {
//                $user->forceFill([
//                    'password' => Hash::make($password),
//                    'remember_token' => Str::random(60),
//                ])->save();
//
//                event(new PasswordReset($user));
//            }
//        );
//
//        if ($status === Password::PASSWORD_RESET) {
//            return response()->json(['message' => __($status)]);
//        }
//
//        return response()->json([
//            'message' => __($status),
//        ], 422);
//    }
//}
//
