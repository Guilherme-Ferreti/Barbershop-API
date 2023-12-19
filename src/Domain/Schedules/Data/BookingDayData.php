<?php

declare(strict_types=1);

namespace Domain\Schedules\Data;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

readonly class BookingDayData
{
    public function __construct(
        public Carbon $date,
        public array $types,
        public bool $is_working_day,
        public ?HolidayData $holiday,
        public Collection $booking_times,
    ) {
    }
}
