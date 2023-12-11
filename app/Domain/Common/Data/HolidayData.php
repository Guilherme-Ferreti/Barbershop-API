<?php

declare(strict_types=1);

namespace App\Domain\Common\Data;

use App\Domain\Common\Enums\HolidayType;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

readonly class HolidayData
{
    public function __construct(
        public Carbon $date,
        public string $name,
        public HolidayType $type,
    ) {
    }

    public static function collectionFromArray(array $array): Collection
    {
        return collect($array)
            ->map(fn ($value) => new static(...[
                'date' => Date::createFromFormat('Y-m-d', $value['date']),
                'name' => $value['name'],
                'type' => HolidayType::from($value['type']),
            ]));
    }
}
