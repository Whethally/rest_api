<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PostCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_list_without_cache_is_slower_than_cached()
    {
        $user = User::factory()->create(); // Create a user for the test
        // Login and get the token
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',  // Ensure this matches your User factory password or set it manually
        ]);

        $token = $response->json('token'); // Assuming 'token' is the key in the response

        $this->withHeader('Authorization', 'Bearer ' . $token);

        Post::factory()->count(100)->create(['author_id' => $user->id]);

        $filters = [];
        $page = 1;
        $key = 'posts:' . md5(json_encode($filters) . '|page=' . $page);

        Cache::forget($key); // очистим кеш

        $start1 = microtime(true);
        $this->getJson('/api/posts?author_id=' . $user->id)->assertOk(); // Make the authenticated request
        $duration1 = microtime(true) - $start1;

        $start2 = microtime(true);
        $this->getJson('/api/posts?author_id=' . $user->id)->assertOk(); // Make the second authenticated request
        $duration2 = microtime(true) - $start2;

        $this->assertTrue($duration2 < $duration1, "Кеш не ускорил запрос");

        echo "\n\n⏱ Первый запрос: {$duration1}s\n⏱ Второй (из кеша): {$duration2}s\n";
    }
}

