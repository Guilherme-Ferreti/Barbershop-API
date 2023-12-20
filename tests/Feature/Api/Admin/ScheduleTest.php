<?php

declare(strict_types=1);

use Domain\Customers\Models\Customer;
use Domain\Schedules\Actions\GetBookingCalendar;
use Domain\Schedules\Models\Schedule;
use Domain\Users\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;

uses()->group('admin');

test('an user can create a schedule without customer phone number', function () {
    $user = User::factory()->create();

    $bookingTime = app(GetBookingCalendar::class)->handle()->firstAvailableBookingTime();

    $route = route('api.admin.schedules.store');

    assertAuthenticatedOnly($route, 'post');
    assertAdminOnly($route, 'post');

    $payload = [
        'customerName'        => fake()->name(),
        'customerPhoneNumber' => null,
        'scheduledTo'         => $bookingTime->date->format('Y-m-d H:i'),
    ];

    $response = actingAs($user)->postJson($route, $payload)->assertCreated();

    assertDatabaseHas(Schedule::class, [
        'customer_name' => $payload['customerName'],
        'scheduled_to'  => $bookingTime->date->format('Y-m-d H:i:s'),
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
        ])
        ->customer->toBeNull();
});

test('an user can create a schedule for non-existing customer', function () {
    $user     = User::factory()->create();
    $customer = Customer::factory()->make();

    $bookingTime = app(GetBookingCalendar::class)->handle()->firstAvailableBookingTime();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $bookingTime->date->format('Y-m-d H:i'),
    ];

    $response = actingAs($user)->postJson(route('api.admin.schedules.store'), $payload)->assertCreated();

    assertDatabaseHas(Customer::class, [
        'name'         => $payload['customerName'],
        'phone_number' => $payload['customerPhoneNumber'],
    ]);

    assertDatabaseHas(Schedule::class, [
        'customer_id'   => Customer::first()->id,
        'customer_name' => $payload['customerName'],
        'scheduled_to'  => $bookingTime->date->format('Y-m-d H:i:s'),
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
        ]);
});

test('an user can create a schedule for existing customer', function () {
    $user     = User::factory()->create();
    $customer = Customer::factory()->create();

    $bookingTime = app(GetBookingCalendar::class)->handle()->firstAvailableBookingTime();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $bookingTime->date->format('Y-m-d H:i'),
    ];

    actingAs($user)->postJson(route('api.admin.schedules.store'), $payload)->assertCreated();

    assertDatabaseCount(Customer::class, 1);

    assertDatabaseHas(Schedule::class, [
        'customer_id'   => $customer->id,
        'customer_name' => $payload['customerName'],
        'scheduled_to'  => $bookingTime->date->format('Y-m-d H:i:s'),
    ]);
});

test('an user can delete a pending schedule', function () {
    $user            = User::factory()->create();
    $pendingSchedule = Schedule::factory()->pending()->for(Customer::factory())->create();

    $route = route('api.admin.pending-schedules.destroy', $pendingSchedule);

    assertAuthenticatedOnly($route, 'delete');
    assertAdminOnly($route, 'delete');

    actingAs($user)->deleteJson($route)->assertNoContent();

    assertModelMissing($pendingSchedule);
});

test('an user cannot delete a completed schedule', function () {
    $user            = User::factory()->create();
    $pendingSchedule = Schedule::factory()->completed()->for(Customer::factory())->create();

    $route = route('api.admin.pending-schedules.destroy', $pendingSchedule);

    actingAs($user)->deleteJson($route)->assertNotFound();

    assertModelExists($pendingSchedule);
});
