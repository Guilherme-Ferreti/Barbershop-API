<?php

declare(strict_types=1);

use Domain\Barbers\Models\Barber;
use Domain\Schedules\Enums\BookingDayType;

use function Pest\Laravel\actingAs;

uses()->group('admin');

test('the booking calendar can be retrieved', function () {
    $barber = Barber::factory()->create();

    $response = actingAs($barber)->getJson(route('api.admin.schedules.booking-calendar'))->assertOk();

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
