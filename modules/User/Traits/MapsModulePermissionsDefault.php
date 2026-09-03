<?php

namespace Mod\User\Traits;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

trait MapsModulePermissions2
{
    /**
     * @param class-string $moduleClass Klasa implementująca ModulePermissions
     */
    protected function seedModulePermissions(string $moduleClass): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($moduleClass::getPermissions() as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        foreach ($moduleClass::getDefaultRoles() as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->givePermissionTo($rolePermissions);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
