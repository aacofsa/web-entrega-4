<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //

    function register(Request $request){
        $validation = Validator::make(
            $request->all(),
            [
                'username' => 'required|unique:dog|max:255',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ],   
        );
        if($validation->fails()){
            return response()->json([
                'error' => 'Bad request',
                'message' => $validation->errors()
            ], 400);
        }
        $valid = $validation->valid();
        
        $dog = new Dog();
        $dog->username = $valid['username'];
        $dog->password = Hash::make($valid['password']);
        $dog->save();
        $token = auth()->attempt($valid);

        return $this->createNewToken($token);
    }

    function login(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required',
                'password' => 'required',
            ],   
        );
        if($validator->fails()){
            return response()->json([
                'error' => 'Bad request',
                'message' => $validator->errors()
            ], 400);
        }
        $validated = $validator->valid();
        $token = Auth::attempt($validated);
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);

    }

    function myProfile(){
        return response()->json(auth()->user());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'dog' => auth()->user()
        ]);
    }
}
