# REST API Blog (Laravel 12)

Проект на Laravel для управления блогом: посты, комментарии, авторизация, роли и права.

## Стек технологий
- Laravel 12
- MySQL
- Docker + Sail
- Redis + Horizon
- JWT (Tymon JWT Auth)
- Role & Permission система

## Быстрый старт

```bash
git clone https://github.com/whethally/rest-api.git
cd rest-api
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
```

## Аутентификация
- POST `/api/register`
- POST `/api/login`
- JWT-токен используется в заголовке:  
  `Authorization: Bearer {token}`

## Основные маршруты

### Посты
- `GET /api/posts`
- `POST /api/posts`
- `PUT /api/posts/{id}`

### Комментарии
- `GET /api/posts/{id}/comments`
- `POST /api/posts/{id}/comments`

### Пользователи
- `GET /api/user`
- `GET /api/token-debug`

### Роли и права
- `GET /api/roles`
- `POST /api/roles`
- `POST /api/roles/{id}/permissions`
- `GET /api/permissions`

## Тестирование

```bash
./vendor/bin/sail artisan test
```

## Структура проекта

- `app/Services`
- `app/Repositories`
- `app/Http/Middleware`

---

## Использование **Insomnia** для тестирования API

1. Установите [Insomnia (версия 11.0.2)](https://github.com/Kong/insomnia/releases/tag/core%4011.0.2)

2. Откройте Insomnia → выберите **"Import/Export"** → **"Import Data"** → **"From File..."**.

3. Выберите файл **`rest-api.yaml`**, который приложен к этому проекту (находится в корне репозитория).

4. После импорта вы получите готовую коллекцию запросов:
   - Регистрация
   - Авторизация
   - Получение информации о пользователе
   - Посты (CRUD)
   - Комментарии
   - Роли и права

![INSOMNIA](https://i.imgur.com/Ilp7nOE.png)

5. Перейдите в **"Environment"** и задайте переменные:
   ```json
   {
     "base_url": "http://localhost/api",
     "token": ""
   }
   ```
![ENV](https://i.imgur.com/t6fGdec.png)
![ENV2](https://i.imgur.com/GUNRWlF.png)

6. После логина скопируйте JWT-токен и вставьте его в поле `token` — запросы начнут автоматически добавлять заголовок:

   ```
   Authorization: Bearer {{ token }}
   ```
![LOGIN](https://i.imgur.com/jFTqag7.png)

Всё готово — теперь вы можете запускать и тестировать все методы API в один клик через Insomnia!
EOF

## Автор
whethally
