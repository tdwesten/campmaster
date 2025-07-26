<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('dashboard');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    Route::get('/bookings', function () {
        return Inertia::render('Bookings/index');
    })->name('bookings.index');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
