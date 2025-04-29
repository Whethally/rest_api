<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    /* Тест: регистрация с невалидным email */
    public function test_register_with_invalid_email()
    {
        $this->expectException(InvalidArgumentException::class);

        $service = new UserService();
        $service->register('not-an-email', 'password123');
    }

    /* Тест: регистрация создает пользователя */
    public function test_register_creates_user()
    {
        $service = new UserService();
        $user = $service->register('valid@example.com', 'password123');

        $this->assertDatabaseHas('users', ['email' => 'valid@example.com']);
        $this->assertInstanceOf(User::class, $user);
        $this->assertNotEquals('password123', $user->password);
    }
}