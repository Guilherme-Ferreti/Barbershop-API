<?php

declare(strict_types=1);

namespace Domain\Schedules\Data;

use Domain\Schedules\Models\Schedule;
use Illuminate\Support\Carbon;

readonly class BookingTimeData
{
    public function __construct(
        public Carbon $date,
        public bool $is_available,
        public ?Schedule $schedule,
    ) {
    }
}
