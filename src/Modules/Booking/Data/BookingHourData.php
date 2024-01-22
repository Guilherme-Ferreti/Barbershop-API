<?php

declare(strict_types=1);

namespace Modules\Booking\Data;

use Illuminate\Support\Carbon;
use Modules\Booking\Models\Appointment;

readonly class BookingHourData
{
    public function __construct(
        public Carbon $date,
        public bool $is_available,
        public ?Appointment $appointment,
    ) {
    }
}
