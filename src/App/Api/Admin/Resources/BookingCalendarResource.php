<?php

declare(strict_types=1);

namespace App\Api\Admin\Resources;

use Domain\Schedules\Data\BookingDayData;
use Domain\Schedules\Data\BookingTimeData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingCalendarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'bookingDays' => $this->booking_days->map(fn (BookingDayData $bookingDay) => [
                'date'         => format_date($bookingDay->date, 'Y-m-d'),
                'types'        => $bookingDay->types,
                'isWorkingDay' => $bookingDay->is_working_day,
                'holiday'      => $bookingDay->holiday,

                'bookingTimes' => $bookingDay->booking_times->map(fn (BookingTimeData $bookingTime) => [
                    'date'        => format_date($bookingTime->date, 'Y-m-d H:i'),
                    'isAvailable' => $bookingTime->is_available,

                    'schedule' => $this->when($bookingTime->schedule, fn () => [
                        'id'                  => $bookingTime->schedule->id,
                        'customerName'        => $bookingTime->schedule->customer_name,
                        'customerPhoneNumber' => $bookingTime->schedule->customer?->phone_number,
                    ], null),
                ]),
            ]),
        ];
    }
}
