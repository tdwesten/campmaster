<?php

use App\Models\Guest;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    Config::set('app.domain', 'campmaster.nl');
    $this->runLandlordMigrations();
});

it('shows the create page for a guest', function () {
    $tenant = Tenant::factory()->create(['name' => 'Camping De Nachtegaal']);
    $tenant->makeCurrent();

    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)->get('/guests/create');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('guests/create')
    );
});

it('stores a new guest via the store route', function () {
    $tenant = Tenant::factory()->create(['name' => 'Camping De Nachtegaal']);
    $tenant->makeCurrent();

    $user = User::factory()->create(['email_verified_at' => now()]);

    $payload = [
        'firstname' => 'Jane',
        'lastname' => 'Doe',
        'email' => 'jane.doe@example.com',
        'street' => 'Main Street',
        'house_number' => '10',
        'postal_code' => '1000 AB',
        'city' => 'Amsterdam',
        'country' => 'Netherlands',
    ];

    $response = $this->actingAs($user)->post('/guests', $payload);

    $guest = Guest::query()->where('email', 'jane.doe@example.com')->first();

    expect($guest)->not()->toBeNull();

    $response->assertRedirect(route('guests.edit', ['guest' => $guest->id]));

    $this->assertDatabaseHas('guests', [
        'id' => $guest->id,
        'firstname' => 'Jane',
        'email' => 'jane.doe@example.com',
        'city' => 'Amsterdam',
    ]);
});
