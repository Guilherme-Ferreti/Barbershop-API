<?php

declare(strict_types=1);

use Domain\Barbers\Models\Barber;
use Domain\Customers\Actions\Login;
use Domain\Customers\Data\Actions\LoginData;
use Domain\Customers\Models\Customer;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses()->group('customer');

test('a customer can login', function () {
    $customer = Customer::factory()->create();

    $payload = [
        'phoneNumber' => $customer->phone_number,
    ];

    $response = postJson(route('api.customer.login'), $payload)->assertOk();

    expect($response->json())
        ->toHaveKeys([
            'accessToken',
            'customer' => [
                'id',
                'name',
                'phoneNumber',
            ],
        ])
        ->customer->id->toBe($customer->id)
        ->customer->name->toBe($customer->name)
        ->customer->phoneNumber->toBe($customer->phone_number);
});

test('a customer cannot login using wrong phone number', function () {
    Customer::factory(10)->create();

    $customer = Customer::factory()->make();

    $payload = [
        'phoneNumber' => $customer->phone_number,
    ];

    postJson(route('api.customer.login'), $payload)->assertUnprocessable();
});

test('jwt authentication works', function () {
    $customer = Customer::factory()->create();

    [, $jwt] = app(Login::class)->handle(new LoginData($customer->phone_number));

    $route = route('api.customer.profile.show');

    getJson($route)->assertUnauthorized();

    getJson($route, ['Authorization' => "Bearer $jwt"])->assertOk();
});

test('customer area is accessable only for authenticated customers', function () {
    $route = route('api.customer.profile.show');

    getJson($route)->assertUnauthorized();
    actingAs(Barber::factory()->create())->getJson($route)->assertUnauthorized();
    actingAs(Barber::factory()->admin()->create())->getJson($route)->assertUnauthorized();
    actingAs(Customer::factory()->create())->getJson($route)->assertOk();
});
