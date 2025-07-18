<?php

use App\Models\Tenant;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Create a tenant
    $this->tenant = Tenant::factory()->create([
        'name' => 'Camping De Nachtegaal',
    ]);

    $this->tenant->makeCurrent();
});

test('guests are redirected to the login page', function () {
    $this->get('/')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/')->assertOk();
});
