<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;
class UserController extends Controller
{
    public function register(Request $request) {
        $record = $request->all();
        $validation = Validator::make($record,[
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);
        if($validation->fails()) {
            return response()->json(['errors'=> $validation->errors()], 422);
        }
        $user = User::create();
        $token = $user->createToken('auth_token')->accessToken;
        return response()->json(['user'=> $user, 'token'=> $token], 200);
    }

    public function login(Request $request) {
        $record = $request->all();
        $validation = Validator::make($record,[
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        if($validation->fails()) {
            return response()->json(['errors'=> $validation->errors()], 422);
        }
        if(Auth::attempt($record)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->accessToken; 
            return response()->json(['data'=> $user, 'token'=> $token], 200);
        }
        else {
            return response()->json(['message'=> 'Invalid Credentials'], 400);
        }
    }

    public function single_user_record(Request $request) {
        $record = User::where('id',$request->id)->first();
        return response()->json(['user'=>$record]);
    }
}
