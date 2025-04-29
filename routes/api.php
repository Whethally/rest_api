<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::middleware('permission:create-posts')->group(function () {
        Route::post('/posts', [PostController::class, 'store']);
    });
    Route::middleware('permission:edit-posts')->group(function () {
        Route::put('/posts/{postId}', [PostController::class, 'update']);
    });

    Route::get('/posts/{postId}/comments', [CommentController::class, 'index']);
    Route::middleware('permission:create-comments')->group(function () {
        Route::post('/posts/{postId}/comments', [CommentController::class, 'store']);
    });

    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/comments', [UserController::class, 'comments']);

    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::post('/roles/{roleId}/permissions', [RoleController::class, 'assignPermissions']);

    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::post('/permissions', [PermissionController::class, 'store']);

    Route::get('/token-debug', [DebugController::class, 'tokenDebug']);
});