<?php

declare(strict_types=1);

use App\Domain\Common\Models\Customer;
use App\Domain\Common\Models\Schedule;

use function Pest\Laravel\actingAs;

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
