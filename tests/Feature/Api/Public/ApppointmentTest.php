<?php

declare(strict_types=1);

use Modules\Auth\Models\Customer;
use Modules\Booking\Actions\GetBookingCalendar;
use Modules\Booking\Models\Appointment;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\postJson;
use function Pest\Laravel\travelTo;

uses()->group('public');

test('an appointment can be created for an existing customer', function () {
    $customer = Customer::factory()->create();

    $hour = app(GetBookingCalendar::class)->handle()->firstAvailableBookingHour();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $hour->date->format('Y-m-d H:i'),
    ];

    $response = postJson(route('api.public.appointments.store'), $payload)->assertCreated();

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
        ]);

    assertDatabaseCount(Customer::class, 1);
    assertDatabaseHas(Appointment::class, [
        'customer_id'   => $customer->id,
        'customer_name' => $payload['customerName'],
        'scheduled_to'  => $hour->date->format('Y-m-d H:i:s'),
    ]);
});

test('an appointment can be created for a non-exiting customer', function () {
    $customer = Customer::factory()->makeOne();

    $hour = app(GetBookingCalendar::class)->handle()->firstAvailableBookingHour();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $hour->date->format('Y-m-d H:i'),
    ];

    postJson(route('api.public.appointments.store'), $payload)->assertCreated();

    assertDatabaseCount(Customer::class, 1);

    assertDatabaseHas(Customer::class, [
        'name'         => $payload['customerName'],
        'phone_number' => $payload['customerPhoneNumber'],
    ]);

    $customer = Customer::first();

    assertDatabaseHas(Appointment::class, [
        'customer_id'   => $customer->id,
        'customer_name' => $payload['customerName'],
        'scheduled_to'  => $hour->date->format('Y-m-d H:i:s'),
    ]);
});

test('an appointment cannot be created if schedule date is already in booked', function () {
    $hour = app(GetBookingCalendar::class)->handle()->firstAvailableBookingHour();

    $appointment = Appointment::factory()->create([
        'scheduled_to' => $hour->date,
    ]);

    $customer = Customer::factory()->makeOne();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $appointment->scheduled_to->format('Y-m-d H:i'),
    ];

    postJson(route('api.public.appointments.store'), $payload)
        ->assertUnprocessable()
        ->assertInvalid('scheduledTo');
});

test('an appointment cannot be created if scheduled to field is before booking hour', function () {
    travelTo(now()->startOfWeek());

    $hour = app(GetBookingCalendar::class)->handle()->firstAvailableBookingHour();

    travelTo($hour->date->toImmutable()->addHour());

    $customer = Customer::factory()->makeOne();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $hour->date->format('Y-m-d H:i'),
    ];

    postJson(route('api.public.appointments.store'), $payload)
        ->assertUnprocessable()
        ->assertInvalid('scheduledTo');
});

test('a pending schedule is deleted when customer creates a new one', function () {
    $customer = Customer::factory()->create();

    $pendingAppointment = Appointment::factory()->pending()->for($customer)->create();

    $hour = app(GetBookingCalendar::class)->handle()->firstAvailableBookingHour();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $hour->date->format('Y-m-d H:i'),
    ];

    postJson(route('api.public.appointments.store'), $payload)->assertCreated();

    assertModelMissing($pendingAppointment);

    assertDatabaseHas(Appointment::class, [
        'customer_id'  => $customer->id,
        'scheduled_to' => $hour->date->format('Y-m-d H:i:s'),
    ]);
});
