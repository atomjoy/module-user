<?php

namespace Mod\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Mod\User\EnabledModules;
use Mod\User\Traits\MapsModulePermissions;

/**
 * Dodaje uprawnienia z listy włączonych modułów EnabledModules::list()
 */
class AppPermissionSeeder extends Seeder
{
    use MapsModulePermissions;

    public function run(): void
    {
        foreach (EnabledModules::list() as $moduleClass) {
            $this->seedModulePermissions($moduleClass);
        }
    }
}
