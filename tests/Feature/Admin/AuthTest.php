<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\postJson;

uses()->group('admin');

test('an admin can login', function () {
    $admin = User::factory()->create();

    $payload = [
        'password' => 'password',
    ];

    $response = postJson(route('admin.login'), $payload)->assertOk();

    expect($response->json())
        ->toHaveKeys([
            'accessToken',
            'user' => [
                'id',
                'name',
            ],
        ])
        ->user->id->toBe($admin->id)
        ->user->name->toBe($admin->name);
});

test('an admin cannot login using non-existing password', function () {
    User::factory(10)->create();

    $payload = [
        'password' => fake()->words(asText: true),
    ];

    postJson(route('admin.login'), $payload)->assertUnprocessable();
});
