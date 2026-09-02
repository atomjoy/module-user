<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

use function Pest\Laravel\artisan;

uses(\Tests\TestCase::class);
uses(RefreshDatabase::class);

test('glowny DatabaseSeeder automatycznie uruchamia UserPermissionsSeeder przez mechanizm autoload', function () {
    // Loaded from this module migration +1 (module-user.dummy.permission)
    expect(Permission::count())->toBe(1);

    artisan('db:seed');

    $this->assertDatabaseHas('permissions', [
        'name' => [
            'module-user.users.create',
            'module-user.users.edit',
            'module-user.users.delete',
            'module-user.users.view',
        ]
    ]);

    expect(Permission::count())->toBeGreaterThan(1);
});
