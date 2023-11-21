<?php

declare(strict_types=1);

namespace App\Domain\Schedules\Data;

use App\Domain\Schedules\Enums\HolidayType;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class HolidayData extends Data
{
    #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
    #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
    public CarbonImmutable $date;

    public string $name;

    public HolidayType $type;
}
