<?php

declare(strict_types=1);

namespace Modules\Booking\Enums;

enum BookingDayType: string
{
    case WEEKDAY = 'weekday';

    case WEEKEND = 'weekend';

    case HOLIDAY = 'holiday';

    public static function asArray(): array
    {
        foreach (self::cases() as $case) {
            $values[$case->name] = $case->value;
        }

        return $values;
    }
}
