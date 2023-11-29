<?php

declare(strict_types=1);

namespace App\Domain\Public\Rules;

use App\Domain\Public\Actions\GetBookingCalendar;
use App\Domain\Public\Data\BookingDayData;
use App\Domain\Public\Data\BookingTimeData;
use Closure;
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
            ->toCollection()
            ->contains(fn (BookingDayData $bookingDay) => $bookingDay->is_working_day
                && $bookingDay->date->isSameDay($value)
                && $bookingDay
                    ->booking_times
                    ->toCollection()
                    ->contains(fn (BookingTimeData $bookingTime) => $bookingTime->is_available && $bookingTime->date->isSameHourAndMinute($value) && $bookingTime->date->greaterThan(now())
                    )
            );

        if (! $isAvailable) {
            $fail('validation.available_booking_time')->translate();
        }
    }
}
