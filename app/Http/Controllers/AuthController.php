<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
 //register
   public function register(Request $request)
{
    $data = $request->validate([
        'first_name' => 'required',
        'last_name'  => 'required',
        'email'      => 'required|email|unique:users,email',
        'password'   => 'required|min:6', 
        'phone'      => 'max:20',
        'address'    => 'string',
    ]);

   
    $userData = array_merge($data, [
        'password' => Hash::make($data['password']),
    ]);

    $user = User::create($userData);

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
            return response()->json([
        'user' => $request->user(),
        'token_from_header' => $request->bearerToken(),
        'auth_check' => auth()->check(),
    ]);

    }

    //logout
      public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout Successfully']);
    }
    public function test(Request $request){

     return response()->json(['message' => ' Successfully']);
    }
}
