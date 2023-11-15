<?php

declare(strict_types=1);

namespace App\Data\Actions;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;

#[MapInputName(CamelCaseMapper::class)]
class StoreScheduleData extends Data
{
    public function __construct(
        public string $customer_phone_number,
        public string $customer_name,
        public Carbon $scheduled_to,
    ) {
    }
}
