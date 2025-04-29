<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /* Регистрирует нового пользователя */
    public function register(Request $request)
    {
        $validator = Validator::make($request->only('email', 'password', 'name'), [
            'email' => ['required', 'email', 'unique:users', 'max:32'],
            'password' => ['required', 'min:6', 'max:32'],
            'name' => ['required', 'string', 'max:32'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token]);
    }

    /* Аутентифицирует пользователя и возвращает JWT токен */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json(['token' => $token]);
    }
}