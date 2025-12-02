<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\MediaUploadTrait;


class ProfileController extends Controller
{
    use MediaUploadTrait;
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
    public function uploadProfileImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048'
        ]);

        $user = auth()->user();

        $this->uploadSingleMedia(
            $user,
            $request,
            'image',
            'avatars'
        );

        return response()->json([
            'status' => 200,
            'message' => 'Profile image updated',
            'data' => [
                'profile_image' => $user->getFirstMediaUrl('avatars')
            ]
        ]);
    }


}
