<?php

declare(strict_types=1);

use App\Domain\Customers\Models\Customer;

use function Pest\Laravel\getJson;

uses()->group('customers');

test('a customer can be retrieved by phone number', function () {
    $customer = Customer::factory()->create();

    $route = route('customers.show', $customer->phone_number);

    $response = getJson($route)->assertOk();

    expect($response->json())
        ->toHaveKeys([
            'id',
            'name',
            'phoneNumber',
        ])
        ->id->toBe($customer->id)
        ->name->toBe($customer->name)
        ->phoneNumber->toBe($customer->phone_number);
});

test('a customer cannot be found using non-existing phone number', function () {
    Customer::factory(5)->create();

    $route = route('customers.show', Customer::factory()->make()->phone_number);

    getJson($route)->assertNotFound();
});
