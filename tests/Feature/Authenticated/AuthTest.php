<?php

declare(strict_types=1);

use App\Domain\Common\Models\Customer;

use function Pest\Laravel\postJson;

uses()->group('authenticated');

test('a customer can login', function () {
    $customer = Customer::factory()->create();

    $payload = [
        'phoneNumber' => $customer->phone_number,
    ];

    $response = postJson(route('authenticated.login'), $payload)->assertOk();

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

test('a customer cannot login using non-existing phone number', function () {
    Customer::factory(10)->create();

    $customer = Customer::factory()->make();

    $payload = [
        'phoneNumber' => $customer->phone_number,
    ];

    postJson(route('authenticated.login'), $payload)->assertUnprocessable();
});
