<?php

use App\Http\Requests\GuestUpdateRequest;
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

        return Inertia::render('bookings/index', [
            'bookings' => [

            ],
        ]);
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

    Route::get('/guests/{guest}/edit', function (Guest $guest) {
        return Inertia::render('guests/edit', [
            'guest' => [
                'id' => $guest->id,
                'firstname' => $guest->firstname,
                'lastname' => $guest->lastname,
                'email' => $guest->email,
                'street' => $guest->street,
                'house_number' => $guest->house_number,
                'postal_code' => $guest->postal_code,
                'city' => $guest->city,
                'country' => $guest->country,
                'created_at' => $guest->created_at?->toISOString(),
            ],
        ]);
    })->name('guests.edit');

    Route::put('/guests/{guest}', function (GuestUpdateRequest $request, Guest $guest) {
        $guest->update($request->validated());

        return redirect()->route('guests.edit', [
            'guest' => $guest,
        ])->with('success', __('messages.guests.edit.subtitle'));
    })->name('guests.update');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
