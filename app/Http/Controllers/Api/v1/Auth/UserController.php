<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    //
    public function index(){
        $users = User::paginate(10);

        return  UserResource::collection($users);
    }

    public function show($id){

        $user = User::findOrFail($id);

        return new UserResource($user);
        
    }

    public function store(StoreUserRequest $request){
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            ]);

        return response()->json("user And Wallet created successfully", 201);
    }

    public function update(UpdateUserRequest $request, $id){
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            ]);

        return new UserResource($user);
        
    }

    public function destroy($id){
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
        
    }
}
