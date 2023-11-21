<?php

declare(strict_types=1);

namespace App\Domain\Schedules\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;

#[MapOutputName(CamelCaseMapper::class)]
class BookingTimeData extends Data
{
    public Carbon $date;

    public bool $is_available;
}
