<?php

namespace Mod\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Mod\User\Enums\UserRole;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (UserRole::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value, 'guard_name' => 'web']);
        }
    }
}
