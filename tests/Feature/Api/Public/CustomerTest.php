<?php

declare(strict_types=1);

use Modules\Auth\Models\Customer;
use Modules\Booking\Models\Appointment;

use function Pest\Laravel\getJson;

uses()->group('public');

test('a customer can be retrieved by phone number', function () {
    $customer = Customer::factory()
        ->has(Appointment::factory()->pending())
        ->has(Appointment::factory(3)->completed())
        ->create();

    $route = route('api.public.customers.show', $customer->phone_number);

    $response = getJson($route)->assertOk();

    expect($response->json())
        ->toHaveKeys([
            'id',
            'name',
            'phoneNumber',
            'pendingAppointment' => [
                'id',
                'customerName',
                'scheduledTo',
                'createdAt',
                'updatedAt',
                'isPending',
                'barber' => ['id', 'name'],
            ],
        ])
        ->id->toBe($customer->id)
        ->name->toBe($customer->name)
        ->phoneNumber->toBe($customer->phone_number)
        ->pendingAppointment->id->toBe($customer->pendingAppointment->id);
});

test('a customer cannot be found using non-existing phone number', function () {
    Customer::factory(5)->create();

    $route = route('api.public.customers.show', Customer::factory()->make()->phone_number);

    getJson($route)->assertNotFound();
});
