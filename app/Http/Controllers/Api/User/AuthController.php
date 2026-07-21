<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email',
            'number' => 'required|digits:10|unique:user,number',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'number' => $validated['number'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'status' => 1,
        ]);

        // $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            // 'token' => $token,
            // 'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'number' => $user->number,
                'role' => $user->role,
                'status' => $user->status,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])
            ->where('role', 'user')
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email or password is incorrect.'],
            ]);
        }

        if (!$user->status) {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Please contact admin.',
            ], 403);
        }

        $user->tokens()->delete();

        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User login successful',
            'token' => $token,
            // 'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully',
        ]);
    }
}