<?php

use Mod\User\Models\User;
use Tests\TestCase;

uses(TestCase::class);

test('moze zmieniac fillable dynamicznie', function () {
    $user = new User();
    $user->fill(['dynamic_field' => 'test_value']);
    expect($user->dynamic_field)->toBeNull();
    $user->addFillableFields(['dynamic_field']);
    $user->fill(['dynamic_field' => 'test_value']);
    expect($user->dynamic_field)->toBe('test_value');
});

test('moze zmieniac hidden dynamicznie', function () {
    $user = new User([
        'name' => 'Alex',
        'email' => 'alex@example.com',
    ]);
    expect($user->toArray())->toHaveKey('email');
    $user->addHiddenFields(['email']);
    expect($user->toArray())->not->toHaveKey('email')
        ->and($user->toArray())->toHaveKey('name');
});
