<?php

declare(strict_types=1);

use Modules\Auth\Models\Customer;
use Modules\Booking\Models\Appointment;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;

uses()->group('customer');

test('a customer\'s appointments can be retrieved', function () {
    $customer = Customer::factory()
        ->has(Appointment::factory()->pending())
        ->has(Appointment::factory(3)->completed())
        ->create();

    $route = route('api.customer.appointments.index');

    assertAuthenticatedOnly($route, 'get');

    $response = actingAs($customer)->getJson($route)->assertOk();

    expect($response->json())
        ->toHaveCount(4)
        ->toHaveKeys([[
            'id',
            'customerName',
            'scheduledTo',
            'createdAt',
            'updatedAt',
            'isPending',
            'barber' => ['id', 'name'],
        ]])
        ->each(fn ($appointment) => $appointment
            ->id->toBeString()
            ->customerName->toBeString()
            ->scheduledTo->toBeString()->toBeDateFormat('Y-m-d H:i')
            ->createdAt->toBeString()->toBeDateFormat('Y-m-d H:i:s')
            ->updatedAt->toBeString()->toBeDateFormat('Y-m-d H:i:s')
            ->isPending->toBeBool()
        );

    expect($response->collect()->where('isPending', true)->count())->toBe(1);
    expect($response->collect()->where('isPending', false)->count())->toBe(3);
});

test('a customer can delete a pending appointment', function () {
    $appointment = Appointment::factory()->pending()->for(Customer::factory())->create();

    $route = route('api.customer.pending-appointments.destroy', $appointment);

    assertAuthenticatedOnly($route, 'delete');
    assertOwnerOnly($route, 'delete');

    actingAs($appointment->customer)->deleteJson($route)->assertNoContent();

    assertModelMissing($appointment);
});

test('a customer cannot delete other customers\'s pending appointments', function () {
    $appointment = Appointment::factory()->pending()->for(Customer::factory())->create();

    $route = route('api.customer.pending-appointments.destroy', $appointment);

    actingAs(Customer::factory()->create())->deleteJson($route)->assertNotFound();

    assertModelExists($appointment);
});

test('a customer completed appointment cannot be deleted', function () {
    $appointment = Appointment::factory()->completed()->for(Customer::factory())->create();

    $route = route('api.customer.pending-appointments.destroy', $appointment);

    actingAs($appointment->customer)->deleteJson($route)->assertNotFound();

    assertModelExists($appointment);
});
