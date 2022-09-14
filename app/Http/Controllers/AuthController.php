<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AuthResource;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validatedData = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        $validatedData['password'] = Hash::make($request->password);
        $user = User::create($validatedData);
        //creating access token
        $accessToken = $user->createToken('authToken')->accessToken;
        
        return response([
            'user' => new AuthResource($user),
            'access_token' => $accessToken,
            'token_type' => 'Bearer'
        ]);
    }
    public function login(LoginRequest $request){
        $credentials = request(['email', 'password']);

        if (! auth()->attempt($credentials)){
            return  response(['message' => 'Invalid Credentials']) ->setStatusCode(401);
        }

        $user = request()->user();
        $rol = $user->roles->pluck('name')->all();

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response([
            'user' => new AuthResource($user),
            'access_token' => $accessToken,
            'role' => $rol[0],
            'token_type' => 'Bearer'
        ]);
    }
    public function logout(Request $request) {
        $request->user()->token()->revoke();
    	return response()->json(['message' => 'Success logout']);
    }
}
