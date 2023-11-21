<?php

declare(strict_types=1);

namespace App\Domain\Schedules\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

#[MapOutputName(CamelCaseMapper::class)]
class BookingTimeData extends Data
{
    #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i')]
    public Carbon $date;

    public bool $is_available;
}
