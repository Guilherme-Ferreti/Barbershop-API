<?php

declare(strict_types=1);

namespace App\Domain\Common\Actions;

use App\Domain\Common\Data\BookingCalendarData;
use App\Domain\Common\Data\BookingDayData;
use App\Domain\Common\Data\BookingTimeData;
use App\Domain\Common\Data\HolidayData;
use App\Domain\Common\Enums\BookingDayType;
use App\Domain\Common\Models\Schedule;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriodImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class GetBookingCalendar
{
    public Collection $schedules;

    public function handle(): BookingCalendarData
    {
        $this->loadSchedules();

        return new BookingCalendarData(
            booking_days: $this->getBookingDays(),
        );
    }

    private function loadSchedules(): void
    {
        $calendarPeriod = $this->getCalendarPeriod();

        $this->schedules = Schedule::query()
            ->with('customer')
            ->whereDateBetween(
                'scheduled_to',
                $calendarPeriod->getStartDate()->format('Y-m-d'),
                $calendarPeriod->getEndDate()->format('Y-m-d')
            )
            ->get();
    }

    private function getBookingDays(): Collection
    {
        return collect($this->getCalendarPeriod())
            ->map(fn (CarbonImmutable $day) => $this->createBookingDay($day));
    }

    private function getCalendarPeriod(): CarbonPeriodImmutable
    {
        $now = now()->startOfDay()->toImmutable();

        return CarbonPeriodImmutable::since($now->startOfDay())->days(1)->until($now->addWeek());
    }

    private function createBookingDay(CarbonImmutable $day): BookingDayData
    {
        $bookingTimes = $this->getBookingTimesForDay($day);

        return new BookingDayData(...[
            'date'           => Carbon::create($day),
            'types'          => $this->getTypesForBookingDay($day),
            'is_working_day' => $this->isWorkingDay($day),
            'holiday'        => $this->getHoliday($day),
            'booking_times'  => $bookingTimes,
        ]);
    }

    private function isWorkingDay(CarbonImmutable $day): bool
    {
        return ! $this->isHoliday($day) && ! $day->isSunday();
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
        $holidays = app(ListHolidays::class)->handle(now()->year);

        return $holidays->first(
            fn (HolidayData $holiday) => $holiday->date->isSameDay($day)
        );
    }

    private function isHoliday(CarbonImmutable $day): bool
    {
        return (bool) $this->getHoliday($day);
    }

    private function getBookingTimesForDay(CarbonImmutable $day): Collection
    {
        return $this->getBookingHours($day)
            ->map(fn (CarbonImmutable $hour) => new BookingTimeData(...[
                'date'         => Carbon::create($hour),
                'is_available' => $this->bookingHourIsAvailable($hour, $day),
                'schedule'     => $this->getScheduleForHour($hour),
            ]))
            ->values();
    }

    private function getBookingHours(CarbonImmutable $day): Collection
    {
        $openingHour         = 8;
        $openingMinute       = 20;
        $lunchTimeFromHour   = 12;
        $lunchTimeFromMinute = 0;
        $lunchTimeToHour     = 13;
        $lunchTimeToMinute   = 0;
        $closingHour         = 20;
        $closingMinute       = 0;

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

    private function bookingHourIsAvailable(CarbonImmutable $hour, CarbonImmutable $day): bool
    {
        if (! $this->isWorkingDay($day)) {
            return false;
        }

        $hourIsPastTodaysHour = $day->isToday() && now()->greaterThan($hour);

        if ($hourIsPastTodaysHour) {
            return false;
        }

        $hourIsBooked = $this->getScheduleForHour($hour);

        if ($hourIsBooked) {
            return false;
        }

        return true;
    }

    private function getScheduleForHour(CarbonImmutable $hour): ?Schedule
    {
        return $this->schedules->firstWhere('scheduled_to', $hour->format('Y-m-d H:i:s'));
    }
}
