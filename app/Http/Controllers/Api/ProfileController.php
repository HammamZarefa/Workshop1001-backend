<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user());
    }


    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $request->user()->update($data);

        return response()->json($request->user());
    }

    public function destroy(Request $request)
    {
        $request->user()->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
