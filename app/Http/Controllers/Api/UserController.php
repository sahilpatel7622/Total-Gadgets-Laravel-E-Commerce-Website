<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'User list',
            'data' => User::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:user,email',
            'number' => 'required|numeric|unique:user,number',
            'password' => 'required|min:6',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'password' => Hash::make($request->password),
        ]);

        Mail::html("
            <h2>Welcome {$user->name}</h2>
            <p>Your account has been created successfully.</p>
            <p><b>Name:</b> {$user->name}</p>
            <p><b>Email:</b> {$user->email}</p>
            <p><b>Number:</b> {$user->number}</p>
            <p><b>Link:</b> http://127.0.0.1:8000/login</p>
        ", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Account Created Successfully');
        });

        return response()->json([
            'status' => true,
            'message' => 'User added successfully and mail sent',
            'data' => $user
        ], 201);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'User details',
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('user', 'email')->ignore($user->id),
            ],
            'number' => [
                'required',
                'numeric',
                Rule::unique('user', 'number')->ignore($user->id),
            ],
            'password' => 'nullable|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->number = $request->number;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();
        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}