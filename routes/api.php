<?php

declare(strict_types=1);

use App\Domain\Customers\Controllers\CustomerController;
use App\Domain\Schedules\Controllers\BookingCalendarController;
use App\Domain\Schedules\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/schedules')
    ->name('schedules.')
    ->controller(ScheduleController::class)
    ->group(function () {
        Route::post('/', 'store')->name('store');

        Route::get('/booking-calendar', BookingCalendarController::class)->name('booking-calendar');
    });

Route::prefix('/customers')
    ->name('customers.')
    ->controller(CustomerController::class)
    ->group(function () {
        Route::get('/{customer:phone_number}', 'show')->name('show');
    });
