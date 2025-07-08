<?php

use App\Models\Guest;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can be created using factory', function () {
    $guest = Guest::factory()->create();

    expect($guest)->toBeInstanceOf(Guest::class)
        ->and($guest->firstname)->not->toBeEmpty()
        ->and($guest->lastname)->not->toBeEmpty()
        ->and($guest->email)->not->toBeEmpty()
        ->and($guest->id)->not->toBeEmpty();

    // Check that the guest exists in the database
    $this->assertDatabaseHas('guests', [
        'id' => $guest->id,
    ]);
});

test('guest can be created with specific attributes', function () {
    $guest = Guest::factory()->create([
        'firstname' => 'John',
        'lastname' => 'Doe',
        'email' => 'john.doe@example.com',
        'country' => 'United Kingdom',
    ]);

    expect($guest->firstname)->toBe('John')
        ->and($guest->lastname)->toBe('Doe')
        ->and($guest->email)->toBe('john.doe@example.com')
        ->and($guest->country)->toBe('United Kingdom');
});

test('guest can be retrieved from database', function () {
    // Create a guest
    $guest = Guest::factory()->create();

    // Retrieve the guest from the database
    $retrievedGuest = Guest::find($guest->id);

    expect($retrievedGuest)->not->toBeNull()
        ->and($retrievedGuest->id)->toBe($guest->id)
        ->and($retrievedGuest->firstname)->toBe($guest->firstname)
        ->and($retrievedGuest->lastname)->toBe($guest->lastname)
        ->and($retrievedGuest->email)->toBe($guest->email);
});
