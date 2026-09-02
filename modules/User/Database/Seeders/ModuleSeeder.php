<?php

namespace Mod\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Mod\User\Models\User;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        User::factory()->create([
            'id' => 1,
            'name' => 'Jan Kowalski',
            'email' => 'jan.kowalski@example.com',
        ]);

        User::factory()->create([
            'id' => 2,
            'name' => 'Anna Nowak',
            'email' => 'anna.nowak@example.com',
        ]);

        User::factory()->count(10)->create();
    }
}
