<?php

use App\Http\Controllers\BookingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('bookings', BookingsController::class)->only(['index']);

    Route::resource('guests', GuestsController::class)->only([
        'index', 'create', 'store', 'edit', 'update',
    ]);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
