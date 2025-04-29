<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostService;
use Auth;
use Cache;
use Illuminate\Http\Request;
use Validator;

class PostController extends Controller
{
    public function __construct(protected PostService $service)
    {
    }

    /* Получает список постов */
    public function index(Request $request)
    {
        return response()->json($this->service->list($request->all()));
    }

    /* Создает новый пост */
    public function store(Request $request)
    {
        $validator = Validator::make($request->only('title', 'content'), [
            'title' => ['required', 'string', 'min:4', 'max:50'],
            'content' => ['required', 'string', 'min:1', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // автора берем из токена
        $data = $request->only('title', 'content');
        $data['author_id'] = auth()->id();

        $post = $this->service->create($data);

        // удалим все кеши постов
        Cache::flush();

        return response()->json($post);
    }

    /* Обновляет существующий пост */
    public function update(Request $request, Post $postId)
    {
        if ($postId->author_id !== Auth::id()) {
            return response()->json([
                'error' => 'Forbidden',
            ], 403);
        }

        $validator = Validator::make($request->only('title', 'content'), [
            'title' => ['required', 'string', 'min:4', 'max:50'],
            'content' => ['required', 'string', 'min:1', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $postId->update($request->only('title', 'content'));

        // очистим кеш
        Cache::flush();

        return response()->json($postId);
    }

    /** Поиск постов */
    public function search(Request $request)
    {
        $results = Post::where('title', 'like', "%{$request->input('search')}%")->get();
        return response()->json($results);
    }
}