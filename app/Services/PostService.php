<?php
namespace App\Services;

use App\Repositories\PostRepository;
use Cache;

/* Сервис для работы с постами, предоставляет бизнес-логику */
class PostService
{
    public function __construct(protected PostRepository $repository)
    {
    }

    /* Получает список постов с применением фильтров и кэшированием */
    public function list(array $filters)
    {
        /* Генерируем уникальный ключ для кэша на основе фильтров и текущей страницы */
        $key = 'posts:' . md5(json_encode($filters) . '|page=' . request('page', 1));

        /* Кэшируем запрос на 60 секунд */
        return Cache::remember($key, 60, function () use ($filters) {
            return $this->repository->getFiltered($filters);
        });
    }

    /* Создает новый пост */
    public function create(array $data)
    {
        return $this->repository->create($data);
    }
}