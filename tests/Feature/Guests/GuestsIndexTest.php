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

it('shows guests paginated and sorted by latest created first', function () {
    // Prepare tenant and set current
    $tenant = Tenant::factory()->create([
        'name' => 'Camping De Nachtegaal',
    ]);
    $tenant->makeCurrent();

    // Authenticated verified user
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    // Seed some guests for this tenant
    Guest::factory()->count(20)->create();

    $latest = Guest::query()->latest()->first();

    $response = $this->actingAs($user)->get('/guests');

    $response->assertOk();

    $response->assertInertia(fn (Assert $page) => $page
        ->component('guests/index')
        ->where('guests.current_page', 1)
        ->where('guests.per_page', 15)
        ->has('guests.data', 15)
        ->where('guests.data.0.id', $latest->id)
    );
});
