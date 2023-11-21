<?php

declare(strict_types=1);

namespace App\Domain\Schedules\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;

#[MapOutputName(CamelCaseMapper::class)]
class BookingTimeData extends Data
{
    public CarbonImmutable $date;

    public bool $is_available;
}
