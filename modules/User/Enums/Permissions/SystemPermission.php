<?php

namespace Mod\User\Enums\Permissions;

/**
 * Zarządzanie systemem nie ludźmi.
 *
 * Module: SYSTEM, Zasób: RECORD, Akcja: FORCE_DELETE
 * Permission: module-name.resource.action
 */
enum SystemPermission: string
{
    // Users
    case USERS_CREATE = 'module-user.users.create';
    case USERS_DELETE = 'module-user.users.delete';
    case USERS_EDIT = 'module-user.users.edit';
    case USERS_VIEW = 'module-user.users.view';

        // Developer
    case BETA_FEATURE_ACCESS = 'module-user.beta-feature.access';

        // No limits
    case LIMIT_BYPASS = 'module-user.limit.bypass';

        // Remove softDeleted
    case RECORD_FORCE_DELETE = 'module-user.record.force-delete';

    /**
     * Zwraca tablicę ze wszystkimi czystymi wartościami string (tekstowymi).
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
