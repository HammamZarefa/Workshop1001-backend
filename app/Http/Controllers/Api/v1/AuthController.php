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


        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ],201);
    }

    public function login(LoginRequest $request)
    {
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
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
