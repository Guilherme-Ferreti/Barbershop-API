<?php

declare(strict_types=1);

use App\Domain\Public\Controllers\BookingCalendarController;
use App\Domain\Public\Controllers\CustomerController;
use App\Domain\Public\Controllers\ScheduleController;
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

Route::name('public.')
    ->group(function () {
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
    });

Route::name('authenticated.')
    ->group(function () {

    });
