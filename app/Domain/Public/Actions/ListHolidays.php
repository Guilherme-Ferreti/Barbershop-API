<?php

declare(strict_types=1);

namespace App\Domain\Public\Actions;

use App\Domain\Public\Data\HolidayData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ListHolidays
{
    public function handle(int $year): Collection
    {
        $holidays = (string) Cache::rememberForever("holidays_$year", fn () => $this->listHolidaysFromApi($year));

        return HolidayData::collectionFromArray(json_decode($holidays, true) ?? []);
    }

    private function listHolidaysFromApi(int $year): string
    {
        return Http::withOptions(['verify' => false])
            ->get("https://brasilapi.com.br/api/feriados/v1/{$year}")
            ->body();
    }
}
