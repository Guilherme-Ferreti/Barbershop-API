<?php

declare(strict_types=1);

use App\Domain\Common\Enums\BookingDayType;

use function Pest\Laravel\getJson;

uses()->group('schedules');

test('the booking calendar can be retrieved', function () {
    $response = getJson(route('public.schedules.booking-calendar.show'))->assertOk();

    expect($response->json())
        ->toHaveKeys([
            'bookingDays' => [[
                'date',
                'types',
                'isWorkingDay',
                'holiday',
                'bookingTimes' => [[
                    'date', 'isAvailable',
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
