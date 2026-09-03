<?php

namespace Mod\User\Contracts;

interface ModulePermissions
{
    /**
     * Zwraca nazwę modułu. (np. Billing)
     */
    public static function getName(): string;

    /**
     * Zwraca unikalny klucz/identyfikator modułu (np. 'module-user', 'system', 'billing').
     */
    public static function getPrefix(): string;

    /**
     * Zwraca listę wszystkich uprawnień rejestrowanych przez ten moduł.
     */
    public static function getPermissions(): array;

    /**
     * Zwraca mapę domyślnych uprawnień dla konkretnych ról-szablonów.
     * Format: ['nazwa_roli' => ['uprawnienie.a.action', 'uprawnienie.b.action']]
     */
    public static function getDefaultRoles(): array;
}
