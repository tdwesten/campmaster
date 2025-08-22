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

it('shows the edit page for a guest', function () {
    $tenant = Tenant::factory()->create(['name' => 'Camping De Nachtegaal']);
    $tenant->makeCurrent();

    $user = User::factory()->create(['email_verified_at' => now()]);
    $guest = Guest::factory()->create();

    $response = $this->actingAs($user)->get("/guests/{$guest->id}/edit");

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('guests/edit')
        ->where('guest.id', $guest->id)
    );
});

it('updates a guest via the update route', function () {
    $tenant = Tenant::factory()->create(['name' => 'Camping De Nachtegaal']);
    $tenant->makeCurrent();

    $user = User::factory()->create(['email_verified_at' => now()]);
    $guest = Guest::factory()->create([
        'firstname' => 'Old',
        'lastname' => 'Name',
        'email' => 'old@example.com',
    ]);

    $payload = [
        'firstname' => 'New',
        'lastname' => 'Name',
        'email' => 'new@example.com',
        'street' => 'New Street',
        'house_number' => '12A',
        'postal_code' => '1234 AB',
        'city' => 'New City',
        'country' => 'Netherlands',
    ];

    $response = $this->actingAs($user)->put("/guests/{$guest->id}", $payload);

    $response->assertRedirect(route('guests.edit', ['guest' => $guest->id]));

    $this->assertDatabaseHas('guests', [
        'id' => $guest->id,
        'firstname' => 'New',
        'email' => 'new@example.com',
        'city' => 'New City',
    ]);
});
