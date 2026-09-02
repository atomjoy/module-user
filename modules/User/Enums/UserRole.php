<?php

namespace Mod\User\Enums;

enum UserRole: string
{
    // Główna administracja i techniczne
    case SUPERADMIN = 'superadmin';
    case ADMIN = 'admin';
    case USER = 'user';

        // Obsługa treści i społeczności
    case MODERATOR = 'moderator';
    case SUPPORT = 'support';
    case EDITOR = 'editor';
    case AUTHOR = 'author';

        // Klienci i biznes
    case ACCOUNTANT = 'accountant';
    case MANAGER = 'manager';
    case PARTNER = 'partner';
    case PREMIUM = 'premium';

        // Bany, specjalne stany konta obsługiwane przez Middleware.
    case DISABLE_LOGIN = 'disable_login';

    /**
     * Zwraca tablicę ze wszystkimi czystymi wartościami string (tekstowymi).
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Zwraca role, które reprezentują blokady lub stany konta.
     * Przydatne w Twoim middleware do szybkiego sprawdzania restrykcji.
     */
    public static function restrictionRoles(): array
    {
        return [
            self::DISABLE_LOGIN->value,
        ];
    }
}
