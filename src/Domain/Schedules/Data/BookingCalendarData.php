<?php

declare(strict_types=1);

namespace Domain\Schedules\Data;

use Illuminate\Support\Collection;

readonly class BookingCalendarData
{
    public function __construct(
        public Collection $booking_days,
    ) {
    }

    public function firstAvailableBookingTime(): ?BookingTimeData
    {
        return $this->booking_days
            ->firstWhere(fn (BookingDayData $bookingDay) => $bookingDay->is_working_day && $bookingDay->booking_times->contains('is_available', true))
            ?->booking_times
            ->firstWhere('is_available', true);
    }
}
