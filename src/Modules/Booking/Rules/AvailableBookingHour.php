<?php

declare(strict_types=1);

namespace Modules\Booking\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;
use Modules\Booking\Actions\GetBookingCalendar;
use Modules\Booking\Data\BookingDayData;
use Modules\Booking\Data\BookingHourData;

class AvailableBookingHour implements ValidationRule
{
    /**
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = Carbon::createFromFormat('Y-m-d H:i', $value);

        $bookingCalendar = app(GetBookingCalendar::class)->handle();

        $isAvailable = $bookingCalendar
            ->days
            ->contains(fn (BookingDayData $day) => $day->is_working_day
                && $day->date->isSameDay($value)
                && $day
                    ->hours
                    ->contains(fn (BookingHourData $hour) => $hour->is_available && $hour->date->isSameHourAndMinute($value)
                    )
            );

        if (! $isAvailable) {
            $fail('validation.available_booking_hour')->translate();
        }
    }
}
