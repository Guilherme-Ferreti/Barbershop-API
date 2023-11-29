<?php

declare(strict_types=1);

use App\Domain\Common\Models\Customer;
use App\Domain\Common\Models\Schedule;

use function Pest\Laravel\getJson;

uses()->group('customers');

test('a customer can be retrieved by phone number', function () {
    $customer = Customer::factory()
        ->has(Schedule::factory()->pending())
        ->has(Schedule::factory(3)->completed())
        ->create();

    $route = route('public.customers.show', $customer->phone_number);

    $response = getJson($route)->assertOk();

    expect($response->json())
        ->toHaveKeys([
            'id',
            'name',
            'phoneNumber',
            'schedules' => [[
                'id',
                'customerName',
                'scheduledTo',
            ]],
        ])
        ->id->toBe($customer->id)
        ->name->toBe($customer->name)
        ->phoneNumber->toBe($customer->phone_number)
        ->schedules->toHaveCount(1);
});

test('a customer cannot be found using non-existing phone number', function () {
    Customer::factory(5)->create();

    $route = route('public.customers.show', Customer::factory()->make()->phone_number);

    getJson($route)->assertNotFound();
});
