<?php

declare(strict_types=1);

namespace Modules\Booking\Actions;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriodImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Booking\Data\BookingCalendarData;
use Modules\Booking\Data\BookingDayData;
use Modules\Booking\Data\BookingHourData;
use Modules\Booking\Data\HolidayData;
use Modules\Booking\Enums\BookingDayType;
use Modules\Booking\Models\Appointment;

class GetBookingCalendar
{
    public Collection $appointments;

    public function handle(): BookingCalendarData
    {
        $this->loadAppointments();

        return new BookingCalendarData(
            days: $this->getBookingDays(),
        );
    }

    private function loadAppointments(): void
    {
        $calendarRange = $this->calendarRange();

        $this->appointments = Appointment::query()
            ->with('customer')
            ->where(
                'scheduled_to',
                '>=',
                $calendarRange->getStartDate()->startOfDay()->format('Y-m-d H:i:s'),
            )
            ->where(
                'scheduled_to',
                '<=',
                $calendarRange->getEndDate()->endOfDay()->format('Y-m-d H:i:s'),
            )
            ->get();
    }

    private function calendarRange(): CarbonPeriodImmutable
    {
        $now = now()->startOfDay()->toImmutable();

        return CarbonPeriodImmutable::since($now->startOfDay())->days(1)->until($now->addWeek());
    }

    private function getBookingDays(): Collection
    {
        return collect($this->calendarRange())
            ->map(fn (CarbonImmutable $day) => $this->getBookingDay($day));
    }

    private function getBookingDay(CarbonImmutable $day): BookingDayData
    {
        return new BookingDayData(...[
            'date'           => Carbon::create($day),
            'types'          => $this->getTypesForBookingDay($day),
            'is_working_day' => $this->isWorkingDay($day),
            'holiday'        => $this->getHoliday($day),
            'hours'          => $this->getBookingHours($day),
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

    private function isWorkingDay(CarbonImmutable $day): bool
    {
        return ! $this->isHoliday($day) && ! $day->isSunday();
    }

    private function getBookingHours(CarbonImmutable $day): Collection
    {
        return $this->possibleBookingHours($day)
            ->map(fn (CarbonImmutable $hour) => new BookingHourData(...[
                'date'         => Carbon::create($hour),
                'is_available' => $this->bookingHourIsAvailable($hour, $day),
                'appointment'  => $this->getAppointmentForHour($hour),
            ]))
            ->values();
    }

    private function getHoliday(CarbonImmutable $day): ?HolidayData
    {
        $holidays = app(ListHolidays::class)->handle(now()->year);

        return $holidays->first(
            fn (HolidayData $holiday) => $holiday->date->isSameDay($day)
        );
    }

    private function possibleBookingHours(CarbonImmutable $day): Collection
    {
        $settings = fn (string $key) => data_get([
            'opening' => ['hour' => 8, 'minute' => 20],
            'closing' => ['hour' => 20, 'minute' => 0],
            'lunch'   => [
                'from' => ['hour' => 12, 'minute' => 59],
                'to'   => ['hour' => 15, 'minute' => 0],
            ],
            'appointment_gap_in_minutes' => 40,
        ], $key);

        $beforeLunch = CarbonPeriodImmutable::between(
            start: $day->setHour($settings('opening.hour'))->setMinute($settings('opening.minute')),
            end: $day->setHour($settings('lunch.from.hour'))->setMinute($settings('lunch.from.minute'))
        )->minutes($settings('appointment_gap_in_minutes'));

        $afterLunch = CarbonPeriodImmutable::between(
            start: $day->setHour($settings('lunch.to.hour'))->setMinute($settings('lunch.to.minute')),
            end: $day->setHour($settings('closing.hour'))->setMinute($settings('closing.minute'))
        )->minutes($settings('appointment_gap_in_minutes'));

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

        $hourIsPastCurrentHour = $day->isToday() && now()->greaterThan($hour);

        if ($hourIsPastCurrentHour) {
            return false;
        }

        $hourIsBooked = $this->getAppointmentForHour($hour);

        if ($hourIsBooked) {
            return false;
        }

        return true;
    }

    private function getAppointmentForHour(CarbonImmutable $hour): ?Appointment
    {
        return $this->appointments->firstWhere('scheduled_to', $hour->format('Y-m-d H:i:s'));
    }

    private function isHoliday(CarbonImmutable $day): bool
    {
        return (bool) $this->getHoliday($day);
    }
}
