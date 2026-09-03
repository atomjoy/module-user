<?php

namespace Mod\User\Abstracts;

use Mod\User\Contracts\ModulePermissions;

abstract class BaseModule implements ModulePermissions
{
	// Domyślny prefix generowany automatycznie z nazwy klasy, jeśli zapomnisz go wpisać
	public static function getPrefix(): string
	{
		// static::class zwróci np. "Mod\User\Module"
		$segments = explode('\\', static::class);

		// Pobieramy drugi segment (indeks 1), czyli "User" i zamieniamy na małe litery
		return isset($segments[1]) ? strtolower($segments[1]) : 'global';
	}

	// Domyślnie brak przypisanych ról-szablonów (nadpisujesz tylko gdy potrzebne)
	public static function getDefaultRoles(): array
	{
		return [];
	}
}
