<?php

declare(strict_types=1);

namespace Modules\Booking\Data;

use Illuminate\Support\Collection;
use Modules\Auth\Models\Barber;

readonly class BookingCalendarData
{
    public function __construct(
        public Barber $barber,
        public Collection $days,
    ) {
    }

    public function firstAvailableBookingHour(): ?BookingHourData
    {
        return $this->days
            ->firstWhere(fn (BookingDayData $day) => $day->is_working_day && $day->hours->contains('is_available', true))
            ?->hours
            ->firstWhere('is_available', true);
    }
}
