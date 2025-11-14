<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends ApiController
{
    public function show(Request $request)
    {
        return $this->resourceResponse(new UserResource($request->user()));
    }


    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();

        $user = $request->user();
        $user->update($data);

        return $this->resourceResponse(new UserResource($user));
    }

    public function destroy(Request $request)
    {
        $request->user()->delete();

        return $this->respondSuccess(null, 'User deleted');
    }
}
