<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Mod\User\EnabledModules;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function getWithModule()
    {
        $groupedPermissions = [];

        $modules = EnabledModules::list();

        foreach ($modules as $moduleClass) {
            $moduleName = $moduleClass::getName();
            $groupedPermissions[$moduleName] = $moduleClass::getPermissions();
        }

        return view('user.permissions.index', compact('groupedPermissions'));
    }

    public function getWithEloquent()
    {
        $permissions = Permission::all();

        // Z 'module-user.users.create' wyciąga 'module-user'
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('module-user::permissions', compact('groupedPermissions'));
    }
}
