# Laravel Custom Modules

Lokalizacja modułów: **modules/{Module}** w projekcie Laravel.

## Require

```sh
# Instalacja
composer require spatie/laravel-permission

# Konfiguracja
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan optimize:clear
php artisan migrate

# Zmień nazwę pliku Spatie permissions db migration
0002_01_01_000003_create_permission_tables.php
```

## Composer

composer.json

```json
{
    "autoload": {
        "psr-4": {
            "Mod\\": "modules"
        }
    }
}
```

## Vite alias

vite.config.js

```js
import path from 'path';

export default defineConfig({
    // Aliasy dla komponentów vue (optional)
    resolve: {
        alias: {
            '@Mod': __dirname + '/modules',
            // '@Mod': path.resolve(__dirname, './modules'),
            // '@ModUser': path.resolve(__dirname, './modules/User/Resources'),
        },
    },
}
```

## TS

tsconfig.json

```json
"compilerOptions": {
        "paths": {
            /* Specify a set of entries that re-map imports to additional lookup locations. */
            "@/*": ["./resources/js/*"],
            "@Mod/*": ["./modules/*"]
        },
}
```

## Provider

bootstrap/providers.php

```php
return [
    // Register ...
    Mod\User\UserServiceProvider::class,
];
```

## Blade

app.blade.php

```php
// Wymagane dla zmian w app.ts
@vite(['resources/css/app.css', 'resources/js/app.ts'])
```

## Inertia

app.ts

```js
import { type DefineComponent } from 'vue';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    progress: {
        color: '#4B5563',
    },
    resolve: name => {
        // Jeśli nazwa komponentu inertia zawiera "::" (np. "User::Profile/Index")
        // Inertia::render('Blog::Website/Index'); -> modules/Blog/Resources/Pages/Website/Index.vue
        if (name.includes('::')) {
            const [module, page] = name.split('::');
            // Szukamy pliku w katalogu konkretnego modułu
            return resolvePageComponent(
                `../../modules/${module}/Resources/Pages/${page}.vue`,
                import.meta.glob<DefineComponent>(`../../modules/**/Resources/Pages/**/*.vue`)
            );
        }
        // Main pages
        return resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue'));
    },
});

initializeTheme();
initializeFlashToast();
```

## Pest config

phpunit.xml

```xml
<testsuites>
    <!-- Dodaj tę sekcję do phpunit.xml -->
    <testsuite name="Modules">
        <directory>modules/*/Tests</directory>
    </testsuite>
</testsuites>
```

## Pest test

Zawsze importuj w teście z modułu!!!

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

// Dodaj importy
use Tests\TestCase;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;
uses(TestCase::class);
uses(RefreshDatabase::class);

// Test dla nowoczesnego widoku Vue + Inertia
test('użytkownik może wyświetlić profil renderowany przez Vue i Inertia', function () {
    // Wyłącz weryfikację w Inertia do testu (nie wie o Pages z modułów):
    config(['inertia.testing.ensure_pages_exist' => false]);

    get('/vue/profil')
        ->assertStatus(200)
        ->assertInertia(function (Assert $page) {
            // Weryfikacja kontraktu danych z frontu i backendu
            $page->component('User::Profile')
                 ->has('name')
                 ->where('name', 'Jan Kowalski');

            // Inteligentne sprawdzenie fizycznego istnienia pliku Vue w module
            $componentName = $page->toArray()['component'];
            [$module, $path] = explode('::', $componentName);
            $expectedFilePath = base_path("modules/{$module}/Resources/Pages/{$path}.vue");
            $this->assertFileExists(
                $expectedFilePath,
                "BŁĄD: Komponent Vue [{$componentName}] nie istnieje w ścieżce: {$expectedFilePath}"
            );
        });
});
```

## Test

Nigdy nie populuj w migracji gdyż **RefreshDatabase** będzie psuło testy!!!

```sh
# Module test
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan test

# Pojedyńczy test
php artisan test --filter=UserModuleTest

# Wszystkie moduły z grupy
php artisan test --testsuite=Modules
```

## Laravel User model (optional, bind() in UserServiceProvider model)

Nie zaszkodzi nadpisać domyślny model użytkownika w Laravel **php artisan config:publish auth**.

```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => Mod\User\Models\User::class,
    ],
],
```

## Struktura katalogów

```txt
modules/User/
├── Config/
│   └── user.php                      # Konfiguracja modułu (np. config('user.per_page'))
│
├── Controllers/
│   ├── UserController.php            # Kontroler dla tradycyjnych tras WEB / Blade
│   └── Api/
│       └── UserController.php        # Kontroler dla tras API (zwracający JSON)
│
├── Middleware/
│   └── IsAdmin.php                   # Przykładowe middleware sprawdzające uprawnienia
│
├── Database/
│   ├── Factories/
│   │   └── UserFactory.php           # Fabryka Eloquent dedykowana dla modelu modułu
│   ├── Migrations/
│   │   └── 2026_01_01_000000_...     # Migracje bazy danych (np. create_module_users_table)
│   └── Seeders/
│       └── ModuleSeeder.php          # Seeder zasilający bazę (Jan Kowalski ID 1, Anna Nowak ID 2)
│
├── Events/
│   └── UserRegistered.php            # Klasa zdarzenia (Event DTO)
│
├── Lang/
│   ├── en.json                       # Tłumaczenia JSON dla języka angielskiego
│   └── pl.json                       # Tłumaczenia JSON dla języka polskiego
│
├── Listeners/
│   └── SendWelcomeEmail.php          # Słuchacz zdarzenia (Listener reagujący na rejestrację)
│
├── Models/
│   └── User.php                      # Model Eloquent powiązany z bazą danych i z UserFactory
│
├── Providers/
│   └── EventServiceProvider.php      # Pod-provider rejestrujący mapę Event -> Listener
│
├── Resources/
│   └── Pages/
│       └── Profile.vue               # Komponent Vue 3 renderowany przez Inertia.js (User::Profile)
│
├── Tests/
│   └── Feature/
│       └── UserModuleTest.php        # Testy Pest (Web, API z JSON, Seeder, asercja pliku Vue)
│
├── Views/
│   ├── permissions.blade.php         # Przykładwy widok uprawnień dla wszystkich modułów (admin)
│   └── profile.blade.php             # Tradycyjny widok Blade (dostępny jako module-user::profile)
│
├── Routes/
│   ├── api.php                       # Plik z trasami API (automatyczny prefiks /api/users)
│   └── web.php                       # Plik z trasami WEB (np. /blade/profile, /vue/profil)
│
├── EnabledModules.php                # Lista modułów uruchomionych w aplikacji (admin)
├── Module.php                        # Ustawienia modułu, uprawnienia userów (admin)
│
└── UserServiceProvider.php           # Główny Service Provider modułu (ładuje trasy, widoki, migracje, config)
```
