<?php

namespace Mod\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Mod\User\Models\User;

class UserModuleSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        // Tworzenie pierwszego użytkownika z sztywnym ID 1
        User::factory()->create([
            'id' => 1,
            'name' => 'Jan Kowalski',
            'email' => 'jan.kowalski@example.com',
        ]);

        // Tworzenie drugiego użytkownika z sztywnym ID 2
        User::factory()->create([
            'id' => 2,
            'name' => 'Anna Nowak',
            'email' => 'anna.nowak@example.com',
        ]);

        // Tworzy 10 losowych użytkowników
        User::factory()->count(10)->create();
    }
}
