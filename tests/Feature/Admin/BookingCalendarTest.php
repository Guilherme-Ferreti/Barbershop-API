<?php

declare(strict_types=1);

use App\Domain\Common\Enums\BookingDayType;
use App\Models\User;

use function Pest\Laravel\actingAs;

uses()->group('admin');

test('the booking calendar can be retrieved', function () {
    $admin = User::factory()->create();

    $response = actingAs($admin)->getJson(route('admin.schedules.booking-calendar.show'))->assertOk();

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
