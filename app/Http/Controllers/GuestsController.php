<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuestUpdateRequest;
use App\Models\Guest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class GuestsController extends Controller
{
    public function index(): Response
    {
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
            'actions' => [
                'create_url' => route('guests.create'),
            ],
        ]);
    }

    public function store(GuestUpdateRequest $request): RedirectResponse
    {
        $guest = Guest::create($request->validated());

        return redirect()->route('guests.edit', [
            'guest' => $guest,
        ])->with('success', __('messages.guests.create.subtitle'));
    }

    public function create(): Response
    {
        return Inertia::render('guests/create');
    }

    public function edit(Guest $guest): Response
    {
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
    }

    public function update(GuestUpdateRequest $request, Guest $guest): RedirectResponse
    {
        $guest->update($request->validated());

        return redirect()->route('guests.edit', [
            'guest' => $guest,
        ])->with('success', __('messages.guests.edit.subtitle'));
    }
}
