<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Profile fetched successfully',
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('user', 'email')->ignore($user->id),
            ],
            'number' => [
                'required',
                'digits:10',
                Rule::unique('user', 'number')->ignore($user->id),
            ],
        ]);

        $user->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'user' => $user->fresh(),
        ]);
    }
}