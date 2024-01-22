<?php

declare(strict_types=1);

use App\Api\Public\Controllers\BookAppointmentController;
use App\Api\Public\Controllers\BookingCalendarController;
use App\Api\Public\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::name('public.')
    ->group(function () {
        Route::post('/schedules', [BookAppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/schedules/booking-calendar', BookingCalendarController::class)->name('booking-calendar');

        Route::get('/customers/{customer:phone_number}', [CustomerController::class, 'show'])->name('customers.show');
    });
