<?php

declare(strict_types=1);

use Domain\Customers\Models\Customer;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

uses()->group('customer');

test('a customer profile can be retrieved', function () {
    $customer = Customer::factory()->create();

    $route = route('api.customer.profile.show');

    assertAuthenticatedOnly($route, 'get');

    $response = actingAs($customer)->getJson($route)->assertOk();

    expect($response->json())
        ->toHaveKeys([
            'id',
            'name',
            'phoneNumber',
        ]);
});

test('a customer can update it\'s profile', function () {
    $customer = Customer::factory()->create();

    $payload = [
        'name' => fake()->name(),
    ];

    $route = route('api.customer.profile.update');

    assertAuthenticatedOnly($route, 'patch');

    actingAs($customer)->patchJson($route, $payload)->assertOk();

    assertDatabaseHas(Customer::class, $payload);
});
