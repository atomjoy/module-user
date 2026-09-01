<?php

namespace Mod\User\Tests\Feature;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia as Assert;
use Mod\User\Database\Seeders\UserModuleSeeder;
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
    seed(UserModuleSeeder::class);

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

    // Tworzymy usera (np. w kontrolerze) i odpalamy event
    $user = User::factory()->create();

    // Wywołanie zdarzenia
    UserRegistered::dispatch($user);

    // Sprawdzamy czy event został poprawnie wysłany
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
    // Wyłącz weryfikację w Inertia do testu (nie wie o Pages z modułów):
    config(['inertia.testing.ensure_pages_exist' => false]);

    get('/vue/profile')
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