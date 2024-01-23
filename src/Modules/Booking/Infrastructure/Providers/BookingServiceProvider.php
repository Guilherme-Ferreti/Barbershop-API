<?php

declare(strict_types=1);

namespace Modules\Booking\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Booking\Models\Appointment;

class BookingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureCustomRouteBindings();
    }

    private function configureCustomRouteBindings(): void
    {
        Route::bind('pendingAppointment', fn (string $value) => Appointment::pending()->findOrFail($value));
    }
}
