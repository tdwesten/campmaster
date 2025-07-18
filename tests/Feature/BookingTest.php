<?php

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create booking', function () {
    $tenant = Tenant::factory()->create();
    $guest = Guest::factory()->create();

    $booking = Booking::create([
        'tenant_id' => $tenant->id,
        'guest_id' => $guest->id,
        'status' => BookingStatus::Pending,
        'start_date' => '2025-08-01',
        'end_date' => '2025-08-07',
        'notes' => 'Test booking',
    ]);

    // Use assertModelExists instead of assertDatabaseHas to avoid date format issues
    $this->assertModelExists($booking);

    // Verify individual fields
    expect($booking->tenant_id)->toBe($tenant->id)
        ->and($booking->guest_id)->toBe($guest->id)
        ->and($booking->status)->toBe(BookingStatus::Pending)
        ->and($booking->start_date->format('Y-m-d'))->toBe('2025-08-01')
        ->and($booking->end_date->format('Y-m-d'))->toBe('2025-08-07')
        ->and($booking->notes)->toBe('Test booking');
});

test('booking belongs to tenant and guest', function () {
    $booking = Booking::factory()->create();

    expect($booking->tenant)->toBeInstanceOf(Tenant::class)
        ->and($booking->guest)->toBeInstanceOf(Guest::class);
});

test('tenant has many bookings', function () {
    $tenant = Tenant::factory()->create();
    Booking::factory()->count(3)->create(['tenant_id' => $tenant->id]);

    expect($tenant->bookings)->toHaveCount(3);
});

test('guest has many bookings', function () {
    $guest = Guest::factory()->create();
    Booking::factory()->count(2)->create(['guest_id' => $guest->id]);

    expect($guest->bookings)->toHaveCount(2);
});

test('booking factory works', function () {
    $booking = Booking::factory()->create();

    expect($booking->id)->not->toBeNull()
        ->and($booking->tenant_id)->not->toBeNull()
        ->and($booking->guest_id)->not->toBeNull()
        ->and($booking->status)->toBeInstanceOf(BookingStatus::class)
        ->and($booking->start_date)->not->toBeNull()
        ->and($booking->end_date)->not->toBeNull();

    // Check that end date is after or equal to start date
    expect($booking->end_date)->toBeGreaterThanOrEqual($booking->start_date);
});
