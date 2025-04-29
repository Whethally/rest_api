<?php

namespace App\Repositories;

use App\Models\Post;

/* PostRepository отвечает за доступ к данным */
class PostRepository
{
    /* Получает отфильтрованные посты с пагинацией */
    public function getFiltered(array $filters = [])
    {
        return Post::with('author')
            ->when($filters['author_id'] ?? null, fn($q, $id) => $q->where('author_id', $id))
            ->when($filters['date'] ?? null, fn($q, $date) => $q->whereDate('created_at', $date))
            ->paginate(10);
    }

    /* Создает новый пост */
    public function create(array $data)
    {
        return Post::create($data);
    }
}