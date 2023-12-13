<?php

declare(strict_types=1);

use App\Domain\Common\Actions\GetBookingCalendar;
use App\Domain\Common\Models\Customer;
use App\Domain\Common\Models\Schedule;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;

uses()->group('admin');

test('an admin can create a schedule without customer phone number', function () {
    $admin = User::factory()->create();

    $bookingTime = app(GetBookingCalendar::class)->handle()->firstAvailableBookingTime();

    $route = route('admin.schedules.store');

    $this->assertAuthenticatedOnly($route, 'post');
    $this->assertAdminOnly($route, 'post');

    $payload = [
        'customerName'        => fake()->name(),
        'customerPhoneNumber' => null,
        'scheduledTo'         => $bookingTime->date->format('Y-m-d H:i'),
    ];

    actingAs($admin)->postJson($route, $payload)->assertCreated();

    assertDatabaseHas(Schedule::class, [
        'customer_name' => $payload['customerName'],
        'scheduled_to'  => $bookingTime->date->format('Y-m-d H:i:s'),
    ]);
});

test('an admin can create a schedule for non-existing customer', function () {
    $admin    = User::factory()->create();
    $customer = Customer::factory()->make();

    $bookingTime = app(GetBookingCalendar::class)->handle()->firstAvailableBookingTime();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $bookingTime->date->format('Y-m-d H:i'),
    ];

    actingAs($admin)->postJson(route('admin.schedules.store'), $payload)->assertCreated();

    assertDatabaseHas(Customer::class, [
        'name'         => $payload['customerName'],
        'phone_number' => $payload['customerPhoneNumber'],
    ]);

    assertDatabaseHas(Schedule::class, [
        'customer_id'   => Customer::first()->id,
        'customer_name' => $payload['customerName'],
        'scheduled_to'  => $bookingTime->date->format('Y-m-d H:i:s'),
    ]);
});

test('an admin can create a schedule for existing customer', function () {
    $admin    = User::factory()->create();
    $customer = Customer::factory()->create();

    $bookingTime = app(GetBookingCalendar::class)->handle()->firstAvailableBookingTime();

    $payload = [
        'customerName'        => $customer->name,
        'customerPhoneNumber' => $customer->phone_number,
        'scheduledTo'         => $bookingTime->date->format('Y-m-d H:i'),
    ];

    actingAs($admin)->postJson(route('admin.schedules.store'), $payload)->assertCreated();

    assertDatabaseCount(Customer::class, 1);

    assertDatabaseHas(Schedule::class, [
        'customer_id'   => $customer->id,
        'customer_name' => $payload['customerName'],
        'scheduled_to'  => $bookingTime->date->format('Y-m-d H:i:s'),
    ]);
});

test('an admin can delete a pending schedule', function () {
    $admin           = User::factory()->create();
    $pendingSchedule = Schedule::factory()->pending()->for(Customer::factory())->create();

    $route = route('admin.pending-schedules.destroy', $pendingSchedule);

    $this->assertAuthenticatedOnly($route, 'delete');
    $this->assertAdminOnly($route, 'delete');

    actingAs($admin)->deleteJson($route)->assertNoContent();

    assertModelMissing($pendingSchedule);
});

test('an admin cannot delete a completed schedule', function () {
    $admin           = User::factory()->create();
    $pendingSchedule = Schedule::factory()->completed()->for(Customer::factory())->create();

    $route = route('admin.pending-schedules.destroy', $pendingSchedule);

    actingAs($admin)->deleteJson($route)->assertNotFound();

    assertModelExists($pendingSchedule);
});
