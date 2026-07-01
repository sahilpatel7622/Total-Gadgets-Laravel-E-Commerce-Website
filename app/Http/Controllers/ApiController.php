<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function list(){
        return User::all();
    }

    public function add_data(Request $req){
        $validate = Validator::make($req->all(),
        [
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'number'=>'required|digits:10|numeric|unique:user,number',
            'password'=>'required|numeric',
            'status'=>'required',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validate->errors()
            ], 422);
        }

        $data = new User(); 
        $data->name = $req->name;
        $data->email = $req->email;
        $data->number = $req->number;
        $data->password = bcrypt($req->password);
        $data->status = $req->status;

        if($data->save()){
            return 'Data Addedd...';
        };
    }

    public function delete_data($id){
        $data = User::destroy($id);
        if($data){
            return "Data Delete...";
        }
    }

    public function search_data($name){
        $data = User::where('name','like',"%$name%")->get();
        if($data){
            return $data;
        }
    }

    public function update_data(Request $req, $id){
        $data = User::find($id);
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validate = Validator::make($req->all(),
        [
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'number'=>'required|digits:10|numeric|unique:user,number',
            'password'=>'required|numeric',
            'status'=>'required',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validate->errors()
            ], 422);
        }
        
        $data->name = $req->name;
        $data->email = $req->email;
        $data->number = $req->number;
        $data->password = bcrypt($req->password);
        $data->status = $req->status;

        $data->save();

        return response()->json([
            'status' => true,
            'message' => 'Data updated successfully',
            'data' => $data
        ]);
    }

    public function register(Request $req){

     $validate = Validator::make($req->all(),
        [
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'number'=>'required|digits:10|numeric|unique:user,number',
            'password'=>'required|numeric',
            'status'=>'required',
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
            'status'   => $req->status,
        ]);

        $token = $user->createToken('myapp')->plainTextToken;

        return['success'=>true,'result'=>$token,"msg"=>'User Register Successfully...'];
    }

    public function login(Request $req){
        $user = User::where('email',$req->email)->first();
        if(!$user || !Hash::check($req->password, $user->password)){
            return "User Not Found...";
        }
        $token = $user->createToken('myapp')->plainTextToken;
        return['success'=>true,'result'=>$token,"msg"=>'User Login Successfully...'];
    }
}