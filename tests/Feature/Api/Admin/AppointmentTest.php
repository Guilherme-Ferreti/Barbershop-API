<?php

declare(strict_types=1);

use Modules\Auth\Models\Barber;
use Modules\Auth\Models\Customer;
use Modules\Booking\Actions\GetBookingCalendar;
use Modules\Booking\Models\Appointment;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;

uses()->group('admin');

test('a barber can create an appointment without customer phone number', function () {
    $barber = Barber::factory()->create();

    $hour = app(GetBookingCalendar::class)->handle($barber)->firstAvailableBookingHour();

    $payload = [
        'barberId'            => Barber::factory()->create()->id,
        'customerName'        => fake()->name(),
        'customerPhoneNumber' => null,
        'scheduledTo'         => $hour->date->format('Y-m-d H:i'),
    ];

    $response = actingAs($barber)->postJson(route('api.admin.appointments.store'), $payload)->assertCreated();

    assertDatabaseHas(Appointment::class, [
        'barber_id'     => $payload['barberId'],
        'customer_name' => $payload['customerName'],
        'scheduled_to'  => $hour->date->format('Y-m-d H:i:s'),
    ]);

    expect($response->json())
        ->toHaveKeys([
            'id',
            'customerName',
            'scheduledTo',
            'isPending',
            'createdAt',
            'updatedAt',
            'customer',
            'barber' => ['id', 'name'],
        ])
        ->customer->toBeNull();
});

test('a barber can create an appointment for non-existing customer', function () {
    $barber   = Barber::factory()->create();
    $customer = Customer::factory()->make();

    $hour = app(GetBookingCalendar::class)->handle($barber)->firstAvailableBookingHour();

    $payload = [
        'barberId'            => $barber->id,
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $hour->date->format('Y-m-d H:i'),
    ];

    $response = actingAs($barber)->postJson(route('api.admin.appointments.store'), $payload)->assertCreated();

    assertDatabaseHas(Customer::class, [
        'name'         => $payload['customerName'],
        'phone_number' => $payload['customerPhoneNumber'],
    ]);

    assertDatabaseHas(Appointment::class, [
        'barber_id'     => $payload['barberId'],
        'customer_id'   => Customer::first()->id,
        'customer_name' => $payload['customerName'],
        'scheduled_to'  => $hour->date->format('Y-m-d H:i:s'),
    ]);

    expect($response->json())
        ->toHaveKeys([
            'id',
            'customerName',
            'scheduledTo',
            'isPending',
            'createdAt',
            'updatedAt',
            'customer' => [
                'id',
                'name',
                'phoneNumber',
            ],
            'barber' => ['id', 'name'],
        ]);
});

test('a barber can create an appointment for existing customer', function () {
    $barber   = Barber::factory()->create();
    $customer = Customer::factory()->create();

    $hour = app(GetBookingCalendar::class)->handle($barber)->firstAvailableBookingHour();

    $payload = [
        'barberId'            => $barber->id,
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $hour->date->format('Y-m-d H:i'),
    ];

    actingAs($barber)->postJson(route('api.admin.appointments.store'), $payload)->assertCreated();

    assertDatabaseCount(Customer::class, 1);

    assertDatabaseHas(Appointment::class, [
        'barber_id'     => $barber->id,
        'customer_id'   => $customer->id,
        'customer_name' => $payload['customerName'],
        'scheduled_to'  => $hour->date->format('Y-m-d H:i:s'),
    ]);
});

test('a barber can delete a pending schedule', function () {
    $barber             = Barber::factory()->create();
    $pendingAppointment = Appointment::factory()->pending()->for(Customer::factory())->create();

    actingAs($barber)->deleteJson(route('api.admin.pending-appointments.destroy', $pendingAppointment))->assertNoContent();

    assertModelMissing($pendingAppointment);
});

test('a barber cannot delete a completed schedule', function () {
    $barber             = Barber::factory()->create();
    $pendingAppointment = Appointment::factory()->completed()->for(Customer::factory())->create();

    actingAs($barber)->deleteJson(route('api.admin.pending-appointments.destroy', $pendingAppointment))->assertNotFound();

    assertModelExists($pendingAppointment);
});
