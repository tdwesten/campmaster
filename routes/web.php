<?php

use App\Models\Guest;
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
        return Inertia::render('bookings/index');
    })->name('bookings.index');

    Route::get('/guests', function () {
        $guests = Guest::query()
            ->latest()
            ->paginate(15)
            ->through(function (Guest $guest) {
                return [
                    'id' => $guest->id,
                    'firstname' => $guest->firstname,
                    'lastname' => $guest->lastname,
                    'email' => $guest->email,
                    'city' => $guest->city,
                    'created_at' => $guest->created_at?->toISOString(),
                ];
            });

        return Inertia::render('guests/index', [
            'guests' => $guests,
        ]);
    })->name('guests.index');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
