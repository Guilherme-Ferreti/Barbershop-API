<?php

declare(strict_types=1);

use App\Domain\Authenticated\Actions\Login;
use App\Domain\Authenticated\Data\LoginData;
use App\Domain\Common\Models\Customer;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\getJson;

uses()->group('authenticated');

test('jwt authentication works', function () {
    $customer = Customer::factory()->create();

    [, $jwt] = app(Login::class)->handle(new LoginData($customer->phone_number));

    $route = route('authenticated.profile.show');

    getJson($route)->assertUnauthorized();

    getJson($route, ['Authorization' => "Bearer $jwt"])->assertOk();
});

test('a customer can update it\'s profile', function () {
    $customer = Customer::factory()->create();

    $payload = [
        'name' => fake()->name(),
    ];

    $route = route('authenticated.profile.update');

    $this->assertAuthenticatedOnly($route, 'patch');

    actingAs($customer)->patchJson($route, $payload)->assertOk();

    assertDatabaseHas(Customer::class, $payload);
});
