<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class AdminAuthController extends Controller
{
    use ApiResponses;


    public function adminLogin(LoginRequest $request)
{
    $data = $request->validated();

    $user = User::where('email', $data['email'])->first();


    if (!$user || !Hash::check($data['password'], $user->password)) {
        return $this->error('Invalid credentials', 401);
    }

    if (!$user->is_admin) {
        return $this->notAuthorized('You are not authorized as admin');
    }

    $tokenName = $request->device_type ?? 'admin_token';
    $token = $user->createToken($tokenName)->plainTextToken;

    return $this->success('Admin logged in successfully', [
        'admin' => new UserResource($user),
        'token' => $token,
        'token_type' => $tokenName,
    ]);
}




public function adminLogout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return $this->success('Admin logged out successfully');
}


}
