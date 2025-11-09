<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }


    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();

        $user = $request->user();
        $user->update($data);

        return new UserResource($user);
    }

    public function destroy(Request $request)
    {
        $request->user()->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
