<?php

declare(strict_types=1);

use Modules\Auth\Models\Barber;
use Modules\Booking\Enums\BookingDayType;

use function Pest\Laravel\actingAs;

uses()->group('admin');

test('the booking calendar can be retrieved', function () {
    $barber = Barber::factory()->count(5)->create()->first();

    $response = actingAs($barber)->getJson(route('api.admin.booking-calendar'))->assertOk();

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
        ->toHaveCount(5)
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
