<?php

namespace Mod\User\Traits;

trait HasModuleSeeder
{
    /**
     * Dodaje automatycznie uprawnienia tego modułu z UserServiceProvider
     */
    public function autoloadModuleSeeder(string $seederClass = \Mod\User\Database\Seeders\UserPermissionsSeeder::class)
    {
        if (! app()->runningInConsole()) {
            return;
        }

        // Jeśli aplikacja uruchamia seedowanie (db:seed), aplikacja zarejestruje ten seeder
        app()->afterResolving('Illuminate\Database\Seeder', function ($seeder) use ($seederClass) {
            if ($seeder instanceof \Database\Seeders\DatabaseSeeder) {
                $seeder->call($seederClass);
            }
        });
    }
}
