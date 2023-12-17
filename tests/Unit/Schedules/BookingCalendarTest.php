<?php

declare(strict_types=1);

use App\Domain\Common\Actions\GetBookingCalendar;
use App\Domain\Common\Data\BookingDayData;
use App\Domain\Common\Data\BookingTimeData;

use function Pest\Laravel\travelTo;

uses()->group('schedules');

test('booking calendar displays correct number of booking days', function () {
    $bookingDaysAmount = 8;

    $bookingCalendar = app(GetBookingCalendar::class)->handle();

    expect($bookingCalendar->booking_days)->toHaveCount($bookingDaysAmount);
});

test('booking calendar creates booking times gaps correctly', function (array $bookingHours) {
    travelTo(now()->startOfDay());

    $bookingCalendar = app(GetBookingCalendar::class)->handle();

    $bookingCalendar
        ->booking_days
        ->where('is_working_day', true)
        ->each(function (BookingDayData $bookingDay) use ($bookingHours) {
            $hours = $bookingDay->booking_times->map(
                fn (BookingTimeData $bookingTime) => $bookingTime->date->format('H:i')
            )->toArray();

            expect($hours)->toEqual($bookingHours);
        });
})->with([
    'Opens at 8:20h, lunch at 13:00h, back from lunch at 15:00h, closes 20:00h, 40 minutes gap' => [[
        '08:20',
        '09:00',
        '09:40',
        '10:20',
        '11:00',
        '11:40',
        '12:20',
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

test('booking calendar sets today\'s booking times as unavailable if current hour is past booking time hour', function (int $currentHour, array $unavailableHours, array $availableHours) {
    travelTo(now()->startOfWeek()->addHours($currentHour));

    $bookingTimes = app(GetBookingCalendar::class)
        ->handle()
        ->booking_days
        ->first()
        ->booking_times;

    $unavailableBookingTimes = $bookingTimes
        ->where('is_available', false)
        ->map(fn (BookingTimeData $bookingTime) => $bookingTime->date->format('H:i'))
        ->values()
        ->toArray();

    expect($unavailableBookingTimes)->toEqual($unavailableHours);

    $availableBookingTimes = $bookingTimes
        ->where('is_available', true)
        ->map(fn (BookingTimeData $bookingTime) => $bookingTime->date->format('H:i'))
        ->values()
        ->toArray();

    expect($availableBookingTimes)->toEqual($availableHours);

})->with([
    'Opens at 8:20h, lunch at 13:00h, back from lunch at 15:00h, closes 20:00h, 40 minutes gap' => [
        'current hour'      => 19,
        'unavailable hours' => [
            '08:20',
            '09:00',
            '09:40',
            '10:20',
            '11:00',
            '11:40',
            '12:20',
            '15:00',
            '15:40',
            '16:20',
            '17:00',
            '17:40',
            '18:20',
        ],
        'available hours' => [
            '19:00',
            '19:40',
        ],
    ],
]);
