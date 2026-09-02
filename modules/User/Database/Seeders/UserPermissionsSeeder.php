<?php
// php artisan db:seed --class="Mod\User\Database\Seeders\UserPermissionsSeeder"

namespace Mod\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Mod\User\Traits\MapsModulePermissions;
use Mod\User\Module;

class UserPermissionsSeeder extends Seeder
{
    use MapsModulePermissions;

    public function run(): void
    {
        $this->seedModulePermissions(Module::class);
    }
}
