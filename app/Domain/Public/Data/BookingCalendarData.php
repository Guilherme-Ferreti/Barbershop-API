<?php

declare(strict_types=1);

namespace App\Domain\Public\Data;

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
            ->firstWhere(fn (BookingDayData $bookingDay) => $bookingDay->is_working_day && $bookingDay->booking_times->toCollection()->contains('is_available', true))
            ?->booking_times
            ->toCollection()
            ->firstWhere('is_available', true);
    }
}
