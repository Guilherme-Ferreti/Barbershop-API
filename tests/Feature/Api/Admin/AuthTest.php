<?php

declare(strict_types=1);

use Modules\Auth\Actions\AuthenticateBarber;
use Modules\Auth\Data\Actions\AuthenticateBarberData;
use Modules\Auth\Models\Barber;
use Modules\Auth\Models\Customer;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses()->group('admin');

test('a barber can login', function () {
    $barber = Barber::factory()->create();

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
        ->user->id->toBe($barber->id)
        ->user->name->toBe($barber->name);
});

test('a barber cannot login using non-existing password', function () {
    Barber::factory(10)->create();

    $payload = [
        'password' => fake()->words(asText: true),
    ];

    postJson(route('api.admin.login'), $payload)->assertUnprocessable();
});

test('jwt authentication works', function () {
    Barber::factory()->create();

    [, $jwt] = app(AuthenticateBarber::class)->handle(new AuthenticateBarberData('password'));

    $route = route('api.admin.profile.show');

    getJson($route)->assertUnauthorized();

    getJson($route, ['Authorization' => "Bearer $jwt"])->assertOk();
});

test('admin area is accessable only for authenticated barbers', function () {
    $route = route('api.admin.profile.show');

    getJson($route)->assertUnauthorized();
    actingAs(Customer::factory()->create())->getJson($route)->assertUnauthorized();
    actingAs(Barber::factory()->create())->getJson($route)->assertOk();
});
