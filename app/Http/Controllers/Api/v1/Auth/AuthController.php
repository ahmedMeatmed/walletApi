<?php

namespace App\Http\Controllers\Api\v1\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(LoginRequest $request){

    
    if(Auth::attempt($request->only('email', 'password'))){
        $user = Auth::user();
        $token = $user->createToken('auth_token');   
        $user->token = $token->plainTextToken; 
        return new UserResource($user);
    }else{
        return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(){
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);

    }
}
