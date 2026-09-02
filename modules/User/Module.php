<?php

namespace Mod\User;

use Mod\User\Contracts\ModulePermissions;
use Mod\User\Enums\Permissions\SystemPermission;

/**
 * Module settings
 */
class Module implements ModulePermissions
{
    public static function getName(): string
    {
        return 'User';
    }

    public static function getPrefix(): string
    {
        // Nazwa modułu z service providera lub np. blog, finance, biling, community, partner, system
        return 'module-user';
    }

    public static function getPermissions(): array
    {
        return [
            ...SystemPermission::values()
        ];
    }

    public static function getDefaultRoles(): array
    {
        // Tworzy role dynamicznie jeżeli nie istnieją (admin, manager)!!!
        return [
            'admin' => [
                SystemPermission::USERS_CREATE->value,
                SystemPermission::USERS_EDIT->value,
                SystemPermission::USERS_VIEW->value,
                SystemPermission::LIMIT_BYPASS->value,
            ],
            'manager' => [
                SystemPermission::USERS_VIEW->value,
            ],
        ];
    }
}
