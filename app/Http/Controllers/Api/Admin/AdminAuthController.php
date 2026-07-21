<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        $admin = Auth::user();

        if ($admin->role != 'admin') {

            Auth::logout();

            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized access.',
            ], 403);
        }

        if ($admin->status == 0) {

            Auth::logout();

            return response()->json([
                'status'  => false,
                'message' => 'Your account is inactive.',
            ], 403);
        }

        $token = $admin->createToken('Admin Token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Admin login successful.',
            'token'   => $token,
            'admin'   => $admin,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Admin logout successful.',
        ], 200);
    }
}