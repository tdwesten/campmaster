<?php

use App\Models\Tenant;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->runLandlordMigrations();

    // Create a tenant
    $this->tenant = Tenant::factory()->create([
        'name' => 'Camping De Nachtegaal',
    ]);

    $this->tenant->makeCurrent();
});

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    // Check if the user was created
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));
});

test('registered users are associated with the current tenant', function () {
    $response = $this->post('/register', [
        'name' => 'Tenant User',
        'email' => 'tenant@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    // Check if the user was created
    $this->assertDatabaseHas('users', [
        'email' => 'tenant@example.com',
    ]);

    // Get the newly registered user
    $user = User::where('email', 'tenant@example.com')->first();

    // Assert that the user exists
    $this->assertNotNull($user, 'User was not created');

    // Assert that the user is associated with the current tenant after manual association
    $this->assertTrue($user->tenants->contains($this->tenant), 'User is not associated with the current tenant after manual association');
    $this->assertEquals(1, $user->tenants->count(), 'User is associated with more than one tenant');
});
