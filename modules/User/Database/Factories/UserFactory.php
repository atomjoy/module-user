<?php

namespace Mod\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mod\User\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('secret'),
        ];
    }
}
