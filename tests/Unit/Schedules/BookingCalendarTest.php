<?php

declare(strict_types=1);

use App\Domain\Public\Actions\GetBookingCalendar;
use App\Domain\Public\Data\BookingDayData;
use App\Domain\Public\Data\BookingTimeData;

uses()->group('schedules');

test('the booking calendar displays correct number of booking days', function () {
    $bookingDaysAmount = 8;

    $bookingCalendar = app(GetBookingCalendar::class)->handle();

    expect($bookingCalendar->booking_days)->toHaveCount($bookingDaysAmount);
});

test('the booking calendar displays booking times gaps correctly', function (array $bookingHours) {
    $bookingCalendar = app(GetBookingCalendar::class)->handle();

    $bookingCalendar
        ->booking_days
        ->toCollection()
        ->where('is_working_day', true)
        ->each(function (BookingDayData $bookingDay) use ($bookingHours) {
            $hours = $bookingDay->booking_times->toCollection()->map(
                fn (BookingTimeData $bookingTime) => $bookingTime->date->format('H:i')
            )->toArray();

            expect($hours)->toEqual($bookingHours);
        });
})->with([
    'Opens at 8:20h, lunch at 12:00h, back from lunch at 13:00h, closes 20:00h, 40 minutes gap' => [[
        '08:20',
        '09:00',
        '09:40',
        '10:20',
        '11:00',
        '11:40',
        '13:00',
        '13:40',
        '14:20',
        '15:00',
        '15:40',
        '16:20',
        '17:00',
        '17:40',
        '18:20',
        '19:00',
        '19:40',
    ]],
]);
