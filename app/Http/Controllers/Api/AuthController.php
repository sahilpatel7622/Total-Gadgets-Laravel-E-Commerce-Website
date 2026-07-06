<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
   
    public function register(Request $req){
        $validate = Validator::make($req->all(),
        [
            'name'=>'required',
            'email'=>'required|email|unique:user,email',
            'number'=>'required|digits:10|numeric|unique:user,number',
            'password'=>'required|numeric',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validate->errors()
            ], 422);
        }

        $user = User::create([
            'name'     => $req->name,
            'email'    => $req->email,
            'number'   => $req->number,
            'password' => Hash::make($req->password),
        ]);

        return['success'=>true, "msg"=>'User Register Successfully...'];
    }

    public function login(Request $req){
        $user = User::where('email',$req->email)->first();
        if(!$user || !Hash::check($req->password, $user->password)){
            return "User Not Found...";
        }
        
        if ($user->status == 'Inactive') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Please contact the administrator.',
            ], 403);
        }

        if ($user->tokens()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'You are already logged in. Please logout first.',
            ], 409);
        }

        $token = $user->createToken('myapp')->plainTextToken;
        return['success'=>true,'result'=>$token,"msg"=>'User Login Successfully...'];
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Logout Successfully'
        ]);
    }

    
}