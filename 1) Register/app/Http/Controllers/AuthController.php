<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register Method

    public function Register(RegisterRequest $request)
    {
        $request->validated();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->passowrd)
        ]);
        $token = $user->createToken('token')->accessToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    // Login Method
    public function Login(LoginRequest $request)
    {
        $request->validated();
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = $request->user();
        $token = $user->createToken('token')->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }
}
