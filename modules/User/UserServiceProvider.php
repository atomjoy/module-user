<?php

namespace Mod\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Mod\User\Traits\HasModuleSeeder;

/**
 * User Service import always first!
 */
class UserServiceProvider extends ServiceProvider
{
    use HasModuleSeeder;

    public function register(): void
    {
        // Konfig
        $this->mergeConfigFrom(__DIR__ . '/Config/Config.php', 'module-user');

        // Ładowanie systemu eventów komponentu
        $this->app->register(\Mod\User\Providers\EventServiceProvider::class);

        // Kiedykolwiek system poprosi o domyślny interfejs użytkownika, daj mu nasz model z modułu
        $this->app->bind(
            \Illuminate\Contracts\Auth\Authenticatable::class,
            \Mod\User\Models\User::class
        );

        // Automatyczne zaimportowanie i rejestracja zewnętrznego Service Providera
        // $this->app->register(\Spatie\Permission\PermissionServiceProvider::class);

        // Możesz też zaimportować swój wewnętrzny sub-provider, np.:
        // $this->app->register(\Mod\User\Providers\AuthServiceProvider::class);

        // if ($this->app->isLocal()) { // Dev services packages }
    }

    public function boot(): void
    {
        // Rejestracja migracji modułu
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

        // Load module permission and roles
        $this->autoloadModuleSeeder(\Mod\User\Database\Seeders\UserPermissionsSeeder::class);

        // Widoki
        $this->loadViewsFrom(__DIR__ . '/Views', 'module-user');

        // Tłumaczenia (używasz potem: __('module-user::messages.welcome'))
        $this->loadTranslationsFrom(__DIR__ . '/../Lang', 'module-user');

        // Tłumaczenia json en.json
        $this->loadJsonTranslationsFrom(__DIR__ . '/Lang');

        // Trasy web
        Route::middleware('web')->namespace('Mod\User\Controllers')->group(__DIR__ . '/Routes/web.php');

        // Trasy api
        Route::middleware('api')->prefix('api')->namespace('Mod\User\Controllers\Api')->group(__DIR__ . '/Routes/api.php');

        // Load routes
        // $this->loadRoutesFrom(__DIR__ . '/Routes/dev.php');
    }
}
