<?php

declare(strict_types=1);

use Modules\Auth\Models\Barber;
use Modules\Booking\Enums\BookingDayType;

use function Pest\Laravel\getJson;

uses()->group('public');

test('the booking calendar for all barbers can be retrieved', function () {
    Barber::factory()->count(3)->create();

    $response = getJson(route('api.public.booking-calendar'))->assertOk();

    expect($response->json())
        ->toHaveKeys([[
            'barber'      => ['id', 'name'],
            'bookingDays' => [[
                'date',
                'types',
                'isWorkingDay',
                'holiday',
                'bookingTimes' => [[
                    'date', 'isAvailable',
                ]],
            ]],
        ]])
        ->toHaveCount(3)
        ->each(fn ($calendar) => $calendar
            ->barber->id->toBeString()
            ->barber->name->toBeString()
            ->bookingDays->each(fn ($day) => $day
            ->date->toBeDateFormat('Y-m-d')
            ->types->each->toBeIn(BookingDayType::asArray())
            ->isWorkingDay->toBeBool()
            ->bookingTimes->each(fn ($hour) => $hour
            ->date->toBeDateFormat('Y-m-d H:i')
            ->isAvailable->toBeBool()
            )
            )
        );
});
