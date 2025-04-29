<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Auth;
use Cache;
use Validator;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /* Получает список комментариев для определенного поста */
    public function index($postId)
    {
        $key = 'comments:post:' . $postId . '|page=' . request('page', 1);

        $comments = Cache::remember($key, 60, function () use ($postId) {
            return Comment::with('user')
                ->where('post_id', $postId)
                ->latest()
                ->paginate(10);
        });

        return response()->json($comments);
    }

    /* Создает новый комментарий для определенного поста */
    public function store(Request $request, $postId)
    {
        $validator = Validator::make($request->only('content'), [
            'content' => ['required', 'string', 'min:1', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // Удалим кеш комментариев только для этого поста
        $key = 'comments:post:' . $postId . '|page=' . request('page', 1);
        Cache::forget($key);

        return response()->json($comment, 201);
    }
}