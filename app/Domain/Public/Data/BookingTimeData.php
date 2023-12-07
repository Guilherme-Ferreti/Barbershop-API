<?php

declare(strict_types=1);

namespace App\Domain\Public\Data;

use Illuminate\Support\Carbon;

class BookingTimeData
{
    public function __construct(
        public Carbon $date,
        public bool $is_available,
    ) {

    }
}
