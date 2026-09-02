<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Mod\User\Database\Seeders\UserPermissionsSeeder;
use Mod\User\Module;

use function Pest\Laravel\seed;

uses(\Tests\TestCase::class);
uses(RefreshDatabase::class);

test('seeder Spatie poprawnie odzwierciedla konfigurację modułu w bazie danych', function () {
    seed(UserPermissionsSeeder::class);
    foreach (Module::getDefaultRoles() as $roleName => $expectedPermissions) {
        $roleInDb = Role::findByName($roleName);
        $dbPermissions = $roleInDb->permissions->pluck('name')->toArray();
        expect($dbPermissions)->toEqualWithDelta($expectedPermissions, 0, 0, true);
    }
});
