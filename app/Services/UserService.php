<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class UserService
{
    public function register(string $email, string $password): User
    {
        // Проверяем, является ли e-mail адрес валидным
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Неверный e-mail адрес");
        }

        // Создаем нового пользователя в базе данных
        return User::create([
            'email' => $email,
            'name' => 'New User',
            'password' => Hash::make($password) // Хэшируем пароль перед сохранением.
        ]);
    }
}