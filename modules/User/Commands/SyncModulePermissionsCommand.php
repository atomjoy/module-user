<?php

namespace Mod\User\Commands;

use Mod\User\EnabledModules; // Lista aktywnych modułów
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Mod\User\Contracts\ModulePermissions;

class SyncModulePermissionsCommand extends Command
{
	// Komenda dostępna w terminalu
	protected $signature = 'modules:sync-permissions';
	protected $description = 'Synchronizuje uprawnienia ze wszystkich aktywnych modułów aplikacji';

	public function handle(): int
	{
		$this->info('Rozpoczynam skanowanie modułów...');

		// Pobierasz klasy modułów spełniające Twój interfejs
		$modules = EnabledModules::getClasses();

		foreach ($modules as $moduleClass) {
			if (!is_subclass_of($moduleClass, ModulePermissions::class)) {
				continue;
			}

			$this->comment("Przetwarzam moduł: {$moduleClass::getName()} [Prefix: {$moduleClass::getPrefix()}]");

			// Rejestracja uprawnień w bazie
			foreach ($moduleClass::getPermissions() as $permissionName) {
				Permission::firstOrCreate([
					'name' => $permissionName,
					'guard_name' => 'web'
				]);
			}

			// Bezpieczne dopisywanie do ról-szablonów bez czyszczenia innych modułów
			foreach ($moduleClass::getDefaultRoles() as $roleName => $permissions) {
				$role = Role::firstOrCreate([
					'name' => $roleName,
					'guard_name' => 'web'
				]);

				// givePermissionTo() gwarantuje, że 3 moduły spokojnie dodadzą dane do tej samej roli
				$role->givePermissionTo($permissions);
			}
		}

		// Pakiet Spatie domyślnie cache\'uje uprawnienia – po synchronizacji warto go wyczyścić
		app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

		$this->info('✅ Uprawnienia modułów zostały pomyślnie zsynchronizowane!');
		return Command::SUCCESS;
	}
}
