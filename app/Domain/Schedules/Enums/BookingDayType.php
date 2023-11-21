<?php

declare(strict_types=1);

namespace App\Domain\Schedules\Enums;

enum BookingDayType: string
{
    case WEEKDAY = 'weekday';

    case WEEKEND = 'weekend';

    case HOLIDAY = 'holiday';
}
