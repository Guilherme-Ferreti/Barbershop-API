<?php

declare(strict_types=1);

namespace Domain\Schedules\Rules;

use Closure;
use Domain\Schedules\Actions\GetBookingCalendar;
use Domain\Schedules\Data\BookingDayData;
use Domain\Schedules\Data\BookingTimeData;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

class AvailableBookingTime implements ValidationRule
{
    /**
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = Carbon::createFromFormat('Y-m-d H:i', $value);

        $bookingCalendar = app(GetBookingCalendar::class)->handle();

        $isAvailable = $bookingCalendar
            ->booking_days
            ->contains(fn (BookingDayData $bookingDay) => $bookingDay->is_working_day
                && $bookingDay->date->isSameDay($value)
                && $bookingDay
                    ->booking_times
                    ->contains(fn (BookingTimeData $bookingTime) => $bookingTime->is_available && $bookingTime->date->isSameHourAndMinute($value)
                    )
            );

        if (! $isAvailable) {
            $fail('validation.available_booking_time')->translate();
        }
    }
}
