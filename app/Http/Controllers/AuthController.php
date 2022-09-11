<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AuthResource;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
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
        
        return [
            'user' => new AuthResource($user),
            'access_token' => $accessToken
        ];
    }
}
