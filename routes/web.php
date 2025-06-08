<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    Route::get('/reservations', function () {
        return Inertia::render('reservations');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
