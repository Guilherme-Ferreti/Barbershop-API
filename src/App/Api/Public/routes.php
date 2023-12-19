<?php

declare(strict_types=1);

use App\Api\Public\Controllers\BookingCalendarController;
use App\Api\Public\Controllers\StoreScheduleController;
use App\Domain\Public\Controllers\ShowCustomerController;
use Illuminate\Support\Facades\Route;

Route::name('public.')
    ->group(function () {
        Route::post('/schedules', StoreScheduleController::class)->name('schedules.store');
        Route::get('/schedules/booking-calendar', BookingCalendarController::class)->name('schedules.booking-calendar.show');

        Route::get('/customers/{customer:phone_number}', ShowCustomerController::class)->name('customers.show');
    });
