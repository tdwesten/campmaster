<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    Config::set('app.domain', 'campmaster.nl');
    $this->runLandlordMigrations();
});

it('redirects home to dashboard', function () {
    $tenant = Tenant::factory()->create(['name' => 'Camping De Nachtegaal']);
    $tenant->makeCurrent();

    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)->get('/');

    // When authenticated, we expect the dashboard page component
    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page->component('dashboard'));
});
