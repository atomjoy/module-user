<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Mod\User\Database\Seeders\UserPermissionsSeeder;
use Mod\User\Module;
use function Pest\Laravel\seed;

uses(\Tests\TestCase::class);
uses(RefreshDatabase::class);

test('role w bazie danych Spatie zawierają dokładnie te same uprawnienia, co definicja modułu', function () {
    seed(UserPermissionsSeeder::class);
    $expectedDefaultRoles = Module::getDefaultRoles();
    // Sprawdzamy każdą rolę zapisaną w bazie Spatie
    foreach ($expectedDefaultRoles as $roleName => $expectedPermissions) {
        $roleInDb = Role::findByName($roleName);
        expect($roleInDb)->not->toBeNull();
        $dbPermissionNames = $roleInDb->permissions->pluck('name')->toArray();
        expect($dbPermissionNames)->toHaveCount(count($expectedPermissions));
        expect($dbPermissionNames)->toEqualWithDelta($expectedPermissions, 0, 0, true);
        foreach ($expectedPermissions as $expectedPermission) {
            expect($dbPermissionNames)->toContain($expectedPermission);
        }
    }
});

test('w bazie danych nie istnieją żadne dzikie lub nieprzypisane uprawnienia', function () {
    $this->seed(UserPermissionsSeeder::class);
    $definedPermissions = Module::getPermissions();
    $dbPermissions = Permission::pluck('name')->toArray();
    foreach ($dbPermissions as $dbPermission) {
        expect([...$definedPermissions])->toContain($dbPermission);
    }
});
