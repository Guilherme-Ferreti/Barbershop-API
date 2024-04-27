<?php

declare(strict_types=1);

namespace App\Api\Public\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Booking\Data\BookingDayData;
use Modules\Booking\Data\BookingHourData;

class BookingCalendarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'barber' => [
                'id'   => $this->barber->id,
                'name' => $this->barber->name,
            ],
            'bookingDays' => $this->days->map(fn (BookingDayData $day) => [
                'date'         => format_date($day->date, 'Y-m-d'),
                'types'        => $day->types,
                'isWorkingDay' => $day->is_working_day,
                'holiday'      => $day->holiday,

                'bookingTimes' => $day->hours->map(fn (BookingHourData $hour) => [
                    'date'        => format_date($hour->date, 'Y-m-d H:i'),
                    'isAvailable' => $hour->is_available,
                ]),
            ]),
        ];
    }
}
