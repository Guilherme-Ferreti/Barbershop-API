<?php

declare(strict_types=1);

namespace App\Domain\Schedules\Actions;

use App\Domain\Schedules\Data\BookingCalendarData;
use App\Domain\Schedules\Data\BookingDayData;
use App\Domain\Schedules\Data\BookingTimeData;
use App\Domain\Schedules\Data\HolidayData;
use App\Domain\Schedules\Enums\BookingDayType;
use App\Domain\Schedules\Models\Schedule;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Carbon\CarbonPeriodImmutable;
use Illuminate\Support\Collection;
use Spatie\LaravelData\DataCollection;

class GetBookingCalendar
{
    const TIMEZONE = 'America/Sao_Paulo';

    public Collection $schedules;

    public function handle(): BookingCalendarData
    {
        $this->loadSchedules();

        return BookingCalendarData::from([
            'booking_days' => $this->getBookingDays(),
        ]);
    }

    private function loadSchedules(): void
    {
        $calendarPeriod = $this->getCalendarPeriod();

        $this->schedules = Schedule::query()
            ->whereDateBetween(
                'scheduled_to',
                $calendarPeriod->getStartDate()->format('Y-m-d'),
                $calendarPeriod->getEndDate()->format('Y-m-d')
            )
            ->get();
    }

    private function getBookingDays(): DataCollection
    {
        return BookingDayData::collection(
            collect($this->getCalendarPeriod())
                ->map(fn (CarbonImmutable $day) => $this->createBookingDay($day))
        );
    }

    private function getCalendarPeriod(): CarbonPeriod
    {
        $now = now(static::TIMEZONE)->startOfDay()->toImmutable();

        return CarbonPeriodImmutable::since($now->startOfDay())->days(1)->until($now->addWeek());
    }

    private function createBookingDay(CarbonImmutable $day): BookingDayData
    {
        $isWorkingDay = ! $this->isHoliday($day) && ! $day->isSunday();

        return BookingDayData::from([
            'date'           => $day,
            'types'          => $this->getTypesForBookingDay($day),
            'is_working_day' => $isWorkingDay,
            'holiday'        => $this->getHoliday($day),
            'bookings_times' => $isWorkingDay ? $this->getBookingTimesForDay($day) : [],
        ]);
    }

    private function getTypesForBookingDay(CarbonImmutable $day): array
    {
        $types = [];

        if ($day->isWeekday()) {
            $types[] = BookingDayType::WEEKDAY;
        }

        if ($day->isWeekend()) {
            $types[] = BookingDayType::WEEKEND;
        }

        if ($this->isHoliday($day)) {
            $types[] = BookingDayType::HOLIDAY;
        }

        return $types;
    }

    private function getHoliday(CarbonImmutable $day): ?HolidayData
    {
        $holidays = app(ListHolidays::class)->handle(now(static::TIMEZONE)->year);

        return $holidays->first(
            fn (HolidayData $holiday) => $holiday->date->format('Y-m-d') === $day->format('Y-m-d')
        );
    }

    private function isHoliday(CarbonImmutable $day): bool
    {
        return (bool) $this->getHoliday($day);
    }

    private function getBookingTimesForDay(CarbonImmutable $day): DataCollection
    {
        $bookingTimes = $this->getDayPeriod($day)
            ->map(fn (CarbonImmutable $period) => [
                'date'         => $period,
                'is_available' => $this->schedules->doesntContain('scheduled_to', $period->format('Y-m-d H:i:s')),
            ]);

        return BookingTimeData::collection($bookingTimes);
    }

    private function getDayPeriod(CarbonImmutable $day): Collection
    {
        $openingHour = 8;
        $openingMinute = 20;
        $lunchTimeFromHour = 12;
        $lunchTimeFromMinute = 0;
        $lunchTimeToHour = 13;
        $lunchTimeToMinute = 0;
        $closingHour = 20;
        $closingMinute = 0;

        $beforeLunch = CarbonPeriodImmutable::between(
            start: $day->setHour($openingHour)->setMinute($openingMinute),
            end: $day->setHour($lunchTimeFromHour)->setMinute($lunchTimeFromMinute)
        )->minutes(40);

        $afterLunch = CarbonPeriodImmutable::between(
            start: $day->setHour($lunchTimeToHour)->setMinute($lunchTimeToMinute),
            end: $day->setHour($closingHour)->setMinute($closingMinute)
        )->minutes(40);

        return collect([
            ...$beforeLunch->toArray(),
            ...$afterLunch->toArray(),
        ]);
    }
}
