<?php

declare(strict_types=1);

use App\Api\Customer\Controllers\AppointmentController;
use App\Api\Customer\Controllers\LoginController;
use App\Api\Customer\Controllers\PendingAppointmentController;
use App\Api\Customer\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::name('customer.')
    ->prefix('/auth')
    ->middleware(['auth', 'customer'])
    ->group(function () {
        Route::post('/login', LoginController::class)->name('login')->withoutMiddleware(['auth', 'customer']);

        Route::get('/me', [ProfileController::class, 'show'])->name('profile.show');
        Route::patch('/me', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/schedules', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::delete('/pending-schedules/{pendingAppointment}', [PendingAppointmentController::class, 'destroy'])->name('pending-appointments.destroy')->can('destroy', 'pendingAppointment');
    });
