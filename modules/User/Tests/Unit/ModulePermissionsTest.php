<?php

use Mod\User\Module;

test('moduł użytkownika zwraca poprawną listę uprawnień', function () {
    $permissions = Module::getPermissions();
    expect($permissions)->toBeArray()->not->toBeEmpty();
    foreach ($permissions as $permission) {
        expect($permission)->toBeString()->toStartWith(Module::getPrefix() . '.');
    }
});

test('domyślne role zawierają tylko zdefiniowane uprawnienia modułu', function () {
    $permissions = Module::getPermissions();
    $defaultRoles = Module::getDefaultRoles();
    expect($defaultRoles)->toBeArray()->toHaveKeys(['admin', 'manager']);
    foreach ($defaultRoles as $role => $rolePermissions) {
        expect($rolePermissions)->toBeArray()->not->toBeEmpty();
        foreach ($rolePermissions as $rolePermission) {
            expect($permissions)->toContain($rolePermission);
        }
    }
});
