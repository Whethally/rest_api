<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /* Получить идентификатор, который будет сохранен в JWT. */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /* Возвращает массив ключ-значение, содержащий любые пользовательские утверждения, которые будут добавлены в JWT. */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /* Атрибуты, которые доступны для массового присвоения. */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /* Атрибуты, которые должны быть скрыты при сериализации. */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* Получить атрибуты, которые должны быть приведены к определенному типу. */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
     * Отношение с моделью Comment.
     * У пользователя может быть много комментариев.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /*
     * Отношение с моделью Role.
     * Пользователь может принадлежать ко многим ролям. 
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /* Проверяет, имеет ли пользователь определенную роль. */
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /* Проверяет, имеет ли пользователь определенное разрешение через любую из своих ролей. */
    public function hasPermission($permission)
    {
        return $this->roles()
            ->whereHas('permissions', function ($q) use ($permission) {
                $q->where('name', $permission);
            })
            ->exists();
    }
}
