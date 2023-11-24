<?php

declare(strict_types=1);

namespace App\Domain\Schedules\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\CamelCaseMapper;

#[MapOutputName(CamelCaseMapper::class)]
class BookingCalendarData extends Data
{
    #[DataCollectionOf(BookingDayData::class)]
    public DataCollection $booking_days;

    public function firstAvailableBookingTime(): ?BookingTimeData
    {
        return $this->booking_days
            ->toCollection()
            ->firstWhere('is_working_day', true)
            ?->booking_times
            ->toCollection()
            ->firstWhere('is_available', true);
    }
}
