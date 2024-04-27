<?php

declare(strict_types=1);

namespace Modules\Booking\Actions;

use Illuminate\Support\Collection;
use Modules\Auth\Models\Barber;

final class GetBookingCalendarForAllBarbers
{
    public function handle(): Collection
    {
        $barbers = Barber::all();

        return $barbers->map(fn (Barber $barber) => app(GetBookingCalendar::class)->handle($barber));
    }
}
