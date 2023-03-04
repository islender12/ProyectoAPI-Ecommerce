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
            ], 401);
        }

        $access_token = auth()->user()->createToken('Auth token')->accessToken;

        return response([
            'user' => auth()->user(),
            'access_token' => $access_token
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'fullname' => $request->fullname,
            'role' => $request->role,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $access_token = $user->createToken('Auth token')->accessToken;
        return response([
            'user' => $user,
            'access_token' => $access_token
        ]);
    }
}
