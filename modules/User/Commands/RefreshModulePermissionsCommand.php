<?php

namespace Mod\User\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:refresh-module-permissions-command')]
#[Description('Refresh user module dummy permissions.')]
class RefreshModulePermissionsCommand extends Command
{
    public function handle()
    {
        $this->info('Aktualizuję uprawnienia modułu ...');

        $this->call('migrate', [
            '--path' => 'modules/Mod/User/Database/Migrations/2026_01_01_000000_user_module.php',
            '--real-path' => true
        ]);

        $this->info('Uprawnienia modułu zostały zaktualizowane!');
    }
}
