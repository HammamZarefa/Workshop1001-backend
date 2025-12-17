<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Controllers\Api\ApiController;
use App\Services\PushNotificationService;

class AuthController extends ApiController
{
    public function __construct(
        protected PushNotificationService $pushService
    ) {}
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
            'fcm_token'  => $data['fcm_token'] ?? null,
            'firebase_token'=>$data['fcm_token'] ?? null,
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

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return response()->json([
            'message' => 'If an account exists, a reset link has been sent to this email.'
        ], 200);

    }


    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {

                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                if (method_exists($user, 'tokens')) {
                    $user->tokens()->delete();
                }
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Your password has been reset successfully.'
            ], 200);
        }

        return response()->json([
            'message' => 'This password reset token is invalid or has expired.'
        ], 422);
    }
    public function updateFcmToken(Request $request)
    {
        return $this->tryCall(function () use ($request) {

            $request->validate([
                'fcm_token' => 'required|string',
            ]);

            $user = auth()->user();

            $user->update([
                'fcm_token' => $request->fcm_token,
            ]);
            $this->pushService->sendToToken(
                $request->fcm_token,
                'Hello ',
                'FCM is working successfully '
            );
            return [
                'message' => 'FCM token updated successfully',
            ];
        });
    }


}
