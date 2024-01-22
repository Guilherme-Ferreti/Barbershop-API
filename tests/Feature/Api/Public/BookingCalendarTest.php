<?php

declare(strict_types=1);

use Modules\Booking\Enums\BookingDayType;

use function Pest\Laravel\getJson;

uses()->group('public');

test('the booking calendar can be retrieved', function () {
    $response = getJson(route('api.public.booking-calendar'))->assertOk();

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
        ->bookingDays->each(fn ($day) => $day
        ->date->toBeDateFormat('Y-m-d')
        ->types->each->toBeIn(BookingDayType::asArray())
        ->isWorkingDay->toBeBool()
        ->bookingTimes->each(fn ($hour) => $hour
        ->date->toBeDateFormat('Y-m-d H:i')
        ->isAvailable->toBeBool()
        )
        );
});
