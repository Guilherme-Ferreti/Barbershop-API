<?php

declare(strict_types=1);

namespace App\Domain\Public\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\CamelCaseMapper;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

#[MapOutputName(CamelCaseMapper::class)]
class BookingDayData extends Data
{
    #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
    public Carbon $date;

    public array $types;

    public bool $is_working_day;

    public ?HolidayData $holiday;

    #[DataCollectionOf(BookingTimeData::class)]
    public DataCollection $booking_times;
}
