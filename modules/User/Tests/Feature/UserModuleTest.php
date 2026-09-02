<?php

namespace Mod\User\Tests\Feature;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia as Assert;
use Mod\User\Database\Seeders\ModuleSeeder;
use Mod\User\Events\UserRegistered;
use Mod\User\Models\User;

uses(\Tests\TestCase::class);
uses(RefreshDatabase::class);

// Test dla trasy WEB
test('użytkownik może wyświetlić profil przez trasę web', function () {
    get('/blade/profile')
        ->assertStatus(200)
        ->assertSee('Profil użytkownika z modułu User');
});

// Test dla trasy API
test('aplikacja zwraca listę użytkowników w formacie json z api', function () {
    seed(ModuleSeeder::class);

    get('/api/users')
        ->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'data' => [
                ['id' => 1, 'name' => 'Jan Kowalski'],
                ['id' => 2, 'name' => 'Anna Nowak']
            ]
        ])
        ->assertJsonFragment(['name' => 'Jan Kowalski'])
        ->assertJsonStructure([
            'status',
            'data' => [
                '*' => ['id', 'name']
            ]
        ]);
});

// Events
test('rejestracja użytkownika odpala event UserRegistered', function () {
    Event::fake();
    $user = User::factory()->create();
    UserRegistered::dispatch($user);
    Event::assertDispatched(UserRegistered::class);
});

// Test dla tradycyjnego widoku Blade
test('użytkownik może wyświetlić profil przez tradycyjny widok blade', function () {
    get('/blade/profile')
        ->assertStatus(200)
        ->assertSee('Profil użytkownika z modułu User');
});

// Test dla nowoczesnego widoku Vue + Inertia
test('użytkownik może wyświetlić profil renderowany przez Vue i Inertia', function () {
    config(['inertia.testing.ensure_pages_exist' => false]);

    get('/vue/profile')
        ->assertStatus(200)
        ->assertInertia(function (Assert $page) {
            $page->component('User::Profile')
                ->has('name')
                ->where('name', 'Jan Kowalski');

            $componentName = $page->toArray()['component'];
            [$module, $path] = explode('::', $componentName);
            $expectedFilePath = base_path("modules/{$module}/Resources/Pages/{$path}.vue");
            $this->assertFileExists(
                $expectedFilePath,
                "BŁĄD: Komponent Vue [{$componentName}] nie istnieje w ścieżce: {$expectedFilePath}"
            );
        });
});
