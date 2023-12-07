<?php

declare(strict_types=1);

use App\Domain\Common\Models\Customer;
use App\Domain\Common\Models\Schedule;
use App\Domain\Public\Actions\GetBookingCalendar;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\postJson;
use function Pest\Laravel\travelTo;

uses()->group('schedules');

test('a schedule can be created with existing customer', function () {
    $customer = Customer::factory()->create();

    $bookingTime = app(GetBookingCalendar::class)->handle()->firstAvailableBookingTime();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $bookingTime->date->format('Y-m-d H:i'),
    ];

    $response = postJson(route('public.schedules.store'), $payload)->assertCreated();

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
    assertDatabaseHas(Schedule::class, [
        'customer_id'   => $customer->id,
        'customer_name' => $payload['customerName'],
        'scheduled_to'  => $bookingTime->date->format('Y-m-d H:i:s'),
    ]);

});

test('a schedule can be created with non-exiting customer', function () {
    $customer = Customer::factory()->makeOne();

    $bookingTime = app(GetBookingCalendar::class)->handle()->firstAvailableBookingTime();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $bookingTime->date->format('Y-m-d H:i'),
    ];

    postJson(route('public.schedules.store'), $payload)->assertCreated();

    assertDatabaseCount(Customer::class, 1);

    assertDatabaseHas(Customer::class, [
        'name'         => $payload['customerName'],
        'phone_number' => $payload['customerPhoneNumber'],
    ]);

    $customer = Customer::first();

    assertDatabaseHas(Schedule::class, [
        'customer_id'   => $customer->id,
        'customer_name' => $payload['customerName'],
        'scheduled_to'  => $bookingTime->date->format('Y-m-d H:i:s'),
    ]);
});

test('a schedule cannot be created if date is already in use', function () {
    $bookingTime = app(GetBookingCalendar::class)->handle()->firstAvailableBookingTime();

    $schedule = Schedule::factory()->create([
        'scheduled_to' => $bookingTime->date,
    ]);

    $customer = Customer::factory()->makeOne();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $schedule->scheduled_to->format('Y-m-d H:i'),
    ];

    postJson(route('public.schedules.store'), $payload)
        ->assertUnprocessable()
        ->assertInvalid('scheduled_to');
});

test('a schedule cannot be created if scheduled to is before booking hour', function () {
    travelTo(now()->startOfWeek());

    $bookingTime = app(GetBookingCalendar::class)->handle()->firstAvailableBookingTime();

    travelTo($bookingTime->date->toImmutable()->addHour());

    $customer = Customer::factory()->makeOne();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $bookingTime->date->format('Y-m-d H:i'),
    ];

    postJson(route('public.schedules.store'), $payload)
        ->assertUnprocessable()
        ->assertInvalid('scheduled_to');
});

test('a pending schedule is replaced when customer creates a new one', function () {
    $customer = Customer::factory()->create();

    $pendingSchedule = Schedule::factory()->pending()->for($customer)->create();

    $bookingTime = app(GetBookingCalendar::class)->handle()->firstAvailableBookingTime();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $bookingTime->date->format('Y-m-d H:i'),
    ];

    postJson(route('public.schedules.store'), $payload)->assertCreated();

    assertModelMissing($pendingSchedule);

    assertDatabaseHas(Schedule::class, [
        'customer_id'  => $customer->id,
        'scheduled_to' => $bookingTime->date->format('Y-m-d H:i:s'),
    ]);
});
