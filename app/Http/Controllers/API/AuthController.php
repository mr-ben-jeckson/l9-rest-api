<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $inputs = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $inputs['name'],
            'email' => $inputs['email'],
            'password' => bcrypt($inputs['password'])
        ]);

        $token = $user->createToken('apitoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'successfully logged out'
        ];
    }

    public function login(Request $request)
    {
        $inputs = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Verifying Account Exits
        $user = User::where('email', $inputs['email'])->first();
        // Next Step for Password Correction
        if(!$user || !Hash::check($inputs['password'], $user->password)){
            return response([
                'message' => 'It does not exist in our credentials',
            ], 401);
        }

        $token = $user->createToken('apitoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}
