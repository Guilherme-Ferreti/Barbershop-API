<?php

declare(strict_types=1);

namespace App\Domain\Schedules\Actions;

use App\Domain\Schedules\Data\HolidayData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Spatie\LaravelData\DataCollection;

class ListHolidays
{
    public function handle(int $year): DataCollection
    {
        $holidays = (string) Cache::rememberForever("holidays_$year", fn () => $this->listHolidaysFromApi($year));

        return HolidayData::collection(json_decode($holidays));
    }

    private function listHolidaysFromApi(int $year): string
    {
        return Http::withOptions(['verify' => false])
            ->get("https://brasilapi.com.br/api/feriados/v1/{$year}")
            ->body();
    }
}
