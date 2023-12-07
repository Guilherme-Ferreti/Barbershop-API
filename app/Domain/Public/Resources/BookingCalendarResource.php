<?php

declare(strict_types=1);

namespace App\Domain\Public\Resources;

use App\Domain\Public\Data\BookingDayData;
use App\Domain\Public\Data\BookingTimeData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingCalendarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'bookingDays' => $this->booking_days->map(fn (BookingDayData $bookingDay) => [
                'date'         => formatDate($bookingDay->date, 'Y-m-d'),
                'types'        => $bookingDay->types,
                'isWorkingDay' => $bookingDay->is_working_day,
                'holiday'      => $bookingDay->holiday,

                'bookingTimes' => $bookingDay->booking_times->map(fn (BookingTimeData $bookingTime) => [
                    'date'        => formatDate($bookingTime->date, 'Y-m-d H:i'),
                    'isAvailable' => $bookingTime->is_available,
                ]),
            ]),
        ];
    }
}
