<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use function Laravel\Prompts\warning;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('permission.table_names.permissions', 'permissions');
        $msg = "Brak tabeli '$tableName'. Uruchom najpierw migracje Spatie lub zmień timestamp pliku migracji na 0002_00_00_000000_create_permission_tables";

        if (!Schema::hasTable($tableName)) {
            warning($msg);
            throw new \RuntimeException($msg);
        }

        // !!! Nigdy nie populuj w migracji gdyż RefreshDatabase będzie psuło testy !!!
        // app()[PermissionRegistrar::class]->forgetCachedPermissions();
        // Permission::create(['name' => 'module-user.dummy.permission']);
    }

    public function down(): void
    {
        Permission::whereIn('name', ['module-user.dummy.permission'])->delete();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
