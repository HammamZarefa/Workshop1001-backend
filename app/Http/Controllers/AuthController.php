<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
 //register
    public function register(Request $request)
    {
        $data = $request->validate([
        'first_name' => 'required',
        'last_name'  => 'required',
        'email'      => 'required|email|unique',
        'password'   => 'required|min:6', 
        'phone'      => 'max:20',
        'address'    => 'string',
        ]);

        $user = User::create([
        'first_name' => $data['first_name'],
        'last_name'  => $data['last_name'],
        'email'      => $data['email'],
        'password'   => Hash::make($data['password']),
        'phone'      => $data['phone'] ,
        'address'    => $data['address'] ,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
         'user' => $user,
        'token' => $token,
        'token_type' => 'Bearer',
    ], 201);
    }
   //login
      public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $data['email'])->first();

      if (! $user || ! Hash::check($data['password'], $user->password)) {
    return response()->json([
        'message' => 'Unauthorized',
    ], 401);
}
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token'=> $token,
            'token_type' => 'Bearer',
        ]);
    }

    //profile
        public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    //logout
      public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout Successfully']);
    }
}
