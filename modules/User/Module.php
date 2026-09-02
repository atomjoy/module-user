<?php

namespace Mod\User;

use Mod\User\Contracts\ModulePermissions;

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
        return 'module-user';
    }

    public static function getPermissions(): array
    {
        return [
            'module-user.users.create',
            'module-user.users.edit',
            'module-user.users.delete',
            'module-user.users.view',
        ];
    }

    public static function getDefaultRoles(): array
    {
        return [
            'admin' => [
                'module-user.users.create',
                'module-user.users.edit',
                'module-user.users.view',
            ],
            'manager' => [
                'module-user.users.view'
            ],
        ];
    }
}
