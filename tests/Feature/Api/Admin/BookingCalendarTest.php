<?php

declare(strict_types=1);

use Domain\Schedules\Enums\BookingDayType;
use Domain\Users\Models\User;

use function Pest\Laravel\actingAs;

uses()->group('admin');

test('the booking calendar can be retrieved', function () {
    $user = User::factory()->create();

    $route = route('api.admin.schedules.booking-calendar');

    assertAuthenticatedOnly($route, 'get');
    assertAdminOnly($route, 'get');

    $response = actingAs($user)->getJson($route)->assertOk();

    expect($response->json())
        ->toHaveKeys([
            'bookingDays' => [[
                'date',
                'types',
                'isWorkingDay',
                'holiday',
                'bookingTimes' => [[
                    'date', 'isAvailable', 'schedule',
                ]],
            ]],
        ])
        ->bookingDays->each(fn ($bookingDay) => $bookingDay
        ->date->toBeDateFormat('Y-m-d')
        ->types->each->toBeIn(BookingDayType::asArray())
        ->isWorkingDay->toBeBool()
        ->bookingTimes->each(fn ($bookingTime) => $bookingTime
        ->date->toBeDateFormat('Y-m-d H:i')
        ->isAvailable->toBeBool()
        )
        );
});
