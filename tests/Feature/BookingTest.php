<?php

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();
    $this->tenant->makeCurrent();
});

test('can create booking', function () {
    $guest = Guest::factory()->create();

    $booking = Booking::create([
        'guest_id' => $guest->id,
        'status' => BookingStatus::Pending,
        'start_date' => '2025-08-01',
        'end_date' => '2025-08-07',
        'notes' => 'Test booking',
    ]);

    // Use assertModelExists instead of assertDatabaseHas to avoid date format issues
    $this->assertModelExists($booking);

    // Verify individual fields
    expect($booking->tenant_id)->toBe($this->tenant->id)
        ->and($booking->guest_id)->toBe($guest->id)
        ->and($booking->status)->toBe(BookingStatus::Pending)
        ->and($booking->start_date->format('Y-m-d'))->toBe('2025-08-01')
        ->and($booking->end_date->format('Y-m-d'))->toBe('2025-08-07')
        ->and($booking->notes)->toBe('Test booking');
});

test('booking belongs to tenant and guest', function () {
    $booking = Booking::factory()->create();

    expect($booking->tenant)->toBeInstanceOf(Tenant::class)
        ->and($booking->tenant->id)->toBe($this->tenant->id)
        ->and($booking->guest)->toBeInstanceOf(Guest::class);
});

test('tenant has many bookings', function () {

    Booking::factory()->count(3)->create(['tenant_id' => $this->tenant->id]);

    expect($this->tenant->bookings)->toHaveCount(3);
});

test('guest has many bookings', function () {
    $guest = Guest::factory()->create();

    Booking::factory()->count(2)->create(['guest_id' => $guest->id]);

    expect($guest->bookings)->toHaveCount(2);
});
