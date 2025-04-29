<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'author_id',
    ];

    /*  Отношение с автором поста */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /*  Отношение с комментариями поста */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}