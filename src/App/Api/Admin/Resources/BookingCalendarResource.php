<?php

declare(strict_types=1);

namespace App\Api\Admin\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Booking\Data\BookingDayData;
use Modules\Booking\Data\BookingHourData;

class BookingCalendarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'bookingDays' => $this->days->map(fn (BookingDayData $day) => [
                'date'         => format_date($day->date, 'Y-m-d'),
                'types'        => $day->types,
                'isWorkingDay' => $day->is_working_day,
                'holiday'      => $day->holiday,

                'bookingTimes' => $day->hours->map(fn (BookingHourData $hour) => [
                    'date'        => format_date($hour->date, 'Y-m-d H:i'),
                    'isAvailable' => $hour->is_available,

                    'schedule' => $this->when($hour->appointment, fn () => [
                        'id'                  => $hour->appointment->id,
                        'customerName'        => $hour->appointment->customer_name,
                        'customerPhoneNumber' => $hour->appointment->customer?->phone_number,
                    ], null),
                ]),
            ]),
        ];
    }
}
