<?php

declare(strict_types=1);

namespace App\Domain\Schedules\Data\Actions;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;

#[MapInputName(CamelCaseMapper::class)]
class StoreScheduleData extends Data
{
    public string $customer_phone_number;

    public string $customer_name;

    #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i')]
    public Carbon $scheduled_to;

    public static function rules(): array
    {
        return [
            'customer_phone_number' => ['required', 'string', 'between:12,13'],
            'customer_name'         => ['required', 'string', 'max:255'],
            'scheduled_to'          => ['required', 'date_format:Y-m-d H:i'],
        ];
    }
}
