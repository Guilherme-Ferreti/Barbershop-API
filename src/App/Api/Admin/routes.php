<?php

declare(strict_types=1);

use App\Api\Admin\Controllers\AppointmentController;
use App\Api\Admin\Controllers\BookingCalendarController;
use App\Api\Admin\Controllers\LoginController;
use App\Api\Admin\Controllers\PendingAppointmentController;
use App\Api\Admin\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::name('admin.')
    ->prefix('/admin')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::post('/login', LoginController::class)->name('login')->withoutMiddleware(['auth', 'admin']);

        Route::get('/me', ProfileController::class)->name('profile.show');

        Route::get('/booking-calendar', BookingCalendarController::class)->name('booking-calendar');

        Route::delete('/pending-schedules/{pendingAppointment}', [PendingAppointmentController::class, 'destroy'])->name('pending-appointments.destroy');

        Route::post('/schedules', [AppointmentController::class, 'store'])->name('appointments.store');
    });
