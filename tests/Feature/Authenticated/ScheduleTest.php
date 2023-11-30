<?php

declare(strict_types=1);

use App\Domain\Common\Models\Customer;
use App\Domain\Common\Models\Schedule;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;

uses()->group('authenticated');

test('a customer\'s schedules can be retrieved', function () {
    $customer = Customer::factory()
        ->has(Schedule::factory()->pending())
        ->has(Schedule::factory(3)->completed())
        ->create();

    $route = route('authenticated.schedules.index');

    $this->assertAuthenticatedOnly($route, 'get');

    $response = actingAs($customer)->getJson($route)->assertOk();

    expect($response->json())
        ->toHaveCount(4)
        ->toHaveKeys([[
            'id',
            'customerName',
            'scheduledTo',
            'createdAt',
            'isPending',
        ]])
        ->each(fn ($schedule) => $schedule
            ->id->toBeString()
            ->customerName->toBeString()
            ->scheduledTo->toBeString()->toBeDateFormat('Y-m-d H:i')
            ->createdAt->toBeString()->toBeDateFormat('Y-m-d H:i:s')
            ->isPending->toBeBool()
        );

    expect($response->collect()->where('isPending', true)->count())->toBe(1);
    expect($response->collect()->where('isPending', false)->count())->toBe(3);
});

test('a customer can delete a pending schedule', function () {
    $schedule = Schedule::factory()->pending()->for(Customer::factory())->create();

    $route = route('authenticated.pending-schedules.destroy', $schedule);

    $this->assertAuthenticatedOnly($route, 'delete');
    $this->assertOwnerOnly($route, 'delete');

    actingAs($schedule->customer)->deleteJson($route)->assertNoContent();

    assertModelMissing($schedule);
});

test('a customer cannot delete other customers\'s pending schedules', function () {
    $schedule = Schedule::factory()->pending()->for(Customer::factory())->create();

    $route = route('authenticated.pending-schedules.destroy', $schedule);

    actingAs(Customer::factory()->create())->deleteJson($route)->assertNotFound();

    assertModelExists($schedule);
});

test('a customer completed schedule cannot be deleted', function () {
    $schedule = Schedule::factory()->completed()->for(Customer::factory())->create();

    $route = route('authenticated.pending-schedules.destroy', $schedule);

    actingAs($schedule->customer)->deleteJson($route)->assertNotFound();

    assertModelExists($schedule);
});
