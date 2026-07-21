<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Users fetched successfully.',
            'users' => $users,
        ], 200);
    }

    public function show($id)
    {
        $user = User::where('role', 'user')->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'User fetched successfully.',
            'user' => $user,
        ], 200);
    }

    public function changeStatus($id)
    {
        $user = User::where('role', 'user')->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User status updated successfully.',
            'user' => $user,
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::where('role', 'user')->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully.',
        ], 200);
    }
}