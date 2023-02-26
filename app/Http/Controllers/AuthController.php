<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        // Verificamos si la informacion validada no existe
        if (!auth()->attempt($validatedData)) {
            return response([
                'message' => "Credenciales Incorrectos",
            ]);
        }

        $access_token = auth()->user()->createToken('Auth token')->accessToken;

        return response([
            'user' => auth()->user(),
            'access_token' => $access_token
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->all());
        $access_token = $user->createToken('Auth token')->accessToken;

        return response([
            'user' => $user,
            'access_token' => $access_token
        ]);
    }
}
