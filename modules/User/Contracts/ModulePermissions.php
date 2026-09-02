<?php

namespace Mod\User\Contracts;

interface ModulePermissions
{
    public static function getName(): string;
    public static function getPrefix(): string;
    public static function getPermissions(): array;
    public static function getDefaultRoles(): array;
}
