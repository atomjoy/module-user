<?php

namespace Mod\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Mod\User\Database\Factories\UserFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
}
