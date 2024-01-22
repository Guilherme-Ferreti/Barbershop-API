<?php

declare(strict_types=1);

use Modules\Booking\Actions\GetBookingCalendar;
use Modules\Booking\Data\BookingDayData;
use Modules\Booking\Data\BookingHourData;

use function Pest\Laravel\travelTo;

uses()->group('appointments');

test('booking calendar displays correct number of booking days', function () {
    $daysAmount = 8;

    $bookingCalendar = app(GetBookingCalendar::class)->handle();

    expect($bookingCalendar->days)->toHaveCount($daysAmount);
});

test('booking calendar creates booking times gaps correctly', function (array $hours) {
    travelTo(now()->startOfDay());

    $bookingCalendar = app(GetBookingCalendar::class)->handle();

    $bookingCalendar
        ->days
        ->where('is_working_day', true)
        ->each(function (BookingDayData $day) use ($hours) {
            $hours = $day->hours->map(
                fn (BookingHourData $hour) => $hour->date->format('H:i')
            )->toArray();

            expect($hours)->toEqual($hours);
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

    $hours = app(GetBookingCalendar::class)
        ->handle()
        ->days
        ->first()
        ->hours;

    $unAvailableBookingHours = $hours
        ->where('is_available', false)
        ->map(fn (BookingHourData $hour) => $hour->date->format('H:i'))
        ->values()
        ->toArray();

    expect($unAvailableBookingHours)->toEqual($unavailableHours);

    $AvailableBookingHours = $hours
        ->where('is_available', true)
        ->map(fn (BookingHourData $hour) => $hour->date->format('H:i'))
        ->values()
        ->toArray();

    expect($AvailableBookingHours)->toEqual($availableHours);

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
