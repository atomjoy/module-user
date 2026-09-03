<?php

namespace Mod\User\Traits;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

trait MapsModulePermissionsDefault
{
    /**
     * @param class-string $moduleClass Klasa implementująca ModulePermissions
     */
    protected function seedModulePermissions(string $moduleClass): void
    {
        // Sprawdzamy czy działa w konsoli ORAZ czy to nie są testy (Pest / PHPUnit)
        $shouldLog = app()->runningInConsole() && !app()->environment('testing');

        if ($shouldLog) {
            // \e[33m to kolor pomarańczowo-żółty dla nagłówka
            $msg = "\e[33m[Module Permissions]\e[0m Ładowanie: {$moduleClass::getName()} [{$moduleClass::getPrefix()}]\n";
            fwrite(STDERR, $msg);

            // Wypisanie pełnej listy wszystkich uprawnień (\e[35m to fioletowy)
            $allModulePermissions = implode(', ', $moduleClass::getPermissions());
            fwrite(STDERR, "   \e[35mUprawnienia modułu:\e[0m [{$allModulePermissions}]\n");
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionsCount = 0;
        $rolesCount = 0;

        // Zapis uprawnień za pomocą bezpiecznego firstOrCreate
        foreach ($moduleClass::getPermissions() as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            $permissionsCount++;
        }

        // Bezpieczne przypisywanie uprawnień do ról oraz generowanie logów ról
        foreach ($moduleClass::getDefaultRoles() as $roleName => $modulePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $existingPermissions = $role->permissions->pluck('name')->toArray();
            $allPermissions = array_unique(array_merge($existingPermissions, $modulePermissions));
            $role->syncPermissions($allPermissions);
            $rolesCount++;

            // Wyświetlanie mapowania dla każdej roli w locie
            if ($shouldLog) {
                $permissionsList = implode(', ', $modulePermissions);
                // \e[1;33m to pogrubiony pomarańczowy, \e[36m to jasnoniebieski/cyjan
                $roleLog = "   \e[1;33m{$roleName}\e[0m => \e[36m[{$permissionsList}]\e[0m\n";
                fwrite(STDERR, $roleLog);
            }
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 3. Zielone podsumowanie na samym końcu przetwarzania modułu
        if ($shouldLog) {
            // \e[32m to kolor zielony dla linii podsumowania
            $successMsg = "   \e[32m└─ Zarejestrowano uprawnienia ({$permissionsCount}) i szablony ról ({$rolesCount}) dla: {$moduleClass::getPrefix()}\e[0m\n\n";
            fwrite(STDERR, $successMsg);
        }
    }
}
