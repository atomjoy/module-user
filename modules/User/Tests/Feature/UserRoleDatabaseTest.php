<?php

use Mod\User\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function Pest\Laravel\artisan;

uses(TestCase::class, RefreshDatabase::class);

test('it verifies that all enum roles exist in the database', function () {
    artisan('db:seed');
    $enumRoles = UserRole::values();
    foreach ($enumRoles as $roleValue) {
        $this->assertDatabaseHas('roles', [
            'name' => $roleValue,
        ]);
    }

    // !!! To tu zabronione (moduły mogą teorzyć role dynamicznie) !!!
    // $dbRoles = DB::table('roles')->count();
    // expect($dbRoles)->toBe(count($enumRoles));
});
