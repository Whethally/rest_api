<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogRequestMiddleware
{
    /* Обрабатывает входящий HTTP-запрос и логирует его */
    public function handle(Request $request, Closure $next): Response
    {
        $user = null;

        // Проверяем, есть ли токен в заголовке Authorization
        if ($request->header('Authorization')) {
            try {
                // Получаем пользователя, если токен есть
                $user = JWTAuth::parseToken()->authenticate();
            } catch (\Exception $e) {
                // Если токен некорректен или отсутствует, оставляем $user равным null
                $user = null;
            }
        }

        Log::info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'body' => $request->all(),
            'ip' => $request->ip(),
            'user_id' => $user ? $user->id : 'guest',  // Логируем id пользователя или 'guest', если пользователь не найден
        ]);

        return $next($request);
    }
}