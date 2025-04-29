<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        // dd(!Auth::check() || !Auth::user()->hasPermission($permission));
        if (!Auth::check() || !Auth::user()->hasPermission($permission)) {
            // return response()->json(['error' => 'Forbidden'], 403);
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'You do not have permission to perform this action.'
            ], 403);
        }

        return $next($request);
    }
}
