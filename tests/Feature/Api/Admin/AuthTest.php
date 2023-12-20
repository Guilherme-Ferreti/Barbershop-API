<?php

declare(strict_types=1);

use Domain\Users\Actions\Login;
use Domain\Users\Data\Actions\LoginData;
use Domain\Users\Models\User;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses()->group('admin');

test('an user can login', function () {
    $user = User::factory()->create();

    $payload = [
        'password' => 'password',
    ];

    $response = postJson(route('api.admin.login'), $payload)->assertOk();

    expect($response->json())
        ->toHaveKeys([
            'accessToken',
            'user' => [
                'id',
                'name',
            ],
        ])
        ->user->id->toBe($user->id)
        ->user->name->toBe($user->name);
});

test('an user cannot login using non-existing password', function () {
    User::factory(10)->create();

    $payload = [
        'password' => fake()->words(asText: true),
    ];

    postJson(route('api.admin.login'), $payload)->assertUnprocessable();
});

test('jwt authentication works', function () {
    User::factory()->create();

    [, $jwt] = app(Login::class)->handle(new LoginData('password'));

    $route = route('api.admin.profile.show');

    getJson($route)->assertUnauthorized();

    getJson($route, ['Authorization' => "Bearer $jwt"])->assertOk();
});
