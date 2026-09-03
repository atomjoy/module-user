<?php

namespace Mod\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Mod\User\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
