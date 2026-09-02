<?php

namespace Mod\User;

class EnabledModules
{
    /**
     * Centralna lista wszystkich aktywnych modułów w aplikacji
     */
    public static function list(): array
    {
        return [
            \Mod\User\Module::class,
            // \Mod\Billing\Module::class,
            // \Mod\Blog\Module::class,
            // \Mod\Forum\Module::class,
            // \Mod\Newsletter\Module::class,
        ];
    }
}
