<?php

declare(strict_types=1);

use App\Domain\Common\Models\Customer;
use App\Domain\Common\Models\Schedule;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;

uses()->group('admin');

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
