<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'Bearer token not provided'], 401);
        }

        $user = JWTAuth::user(); // Получаем пользователя из JWT токена

        return response()->json(['message' => 'User data retrieved successfully', 'user' => $user, 'Bearers' => 'Bearer ' . $request->bearerToken()], 200);
    }

    public function comments(Request $request)
    {
        $user = $request->user();

        $comments = $user->comments()->with('post')->latest()->paginate(10);

        return response()->json($comments);
    }
}
