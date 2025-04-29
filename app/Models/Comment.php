<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['post_id', 'user_id', 'content'];

    /*  Отношение с пользователем, оставившим комментарий */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*  Отношение с постом, к которому относится комментарий */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}