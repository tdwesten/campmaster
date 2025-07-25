<?php

use App\Domain\Booking\Actions\CreateBookingAction;
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

    // Create booking using the event sourcing action
    $bookingUuid = (new CreateBookingAction)->execute(
        guestUuid: $guest->id,
        startDate: '2025-08-01',
        endDate: '2025-08-07',
        notes: 'Test booking'
    );

    // Retrieve the booking from the database
    $booking = Booking::find($bookingUuid);

    // Verify the booking exists
    $this->assertNotNull($booking);

    // Verify individual fields
    expect($booking->tenant_id)->toBe($this->tenant->id)
        ->and($booking->guest_id)->toBe($guest->id)
        ->and($booking->status)->toBe(BookingStatus::Pending)
        ->and($booking->start_date->format('Y-m-d'))->toBe('2025-08-01')
        ->and($booking->end_date->format('Y-m-d'))->toBe('2025-08-07')
        ->and($booking->notes)->toBe('Test booking');
});

test('booking belongs to tenant and guest', function () {
    $guest = Guest::factory()->create();

    // Create booking using the event sourcing action
    $bookingUuid = (new CreateBookingAction)->execute(
        guestUuid: $guest->id,
        startDate: '2025-08-01',
        endDate: '2025-08-07'
    );

    $booking = Booking::find($bookingUuid);

    expect($booking->tenant)->toBeInstanceOf(Tenant::class)
        ->and($booking->tenant->id)->toBe($this->tenant->id)
        ->and($booking->guest)->toBeInstanceOf(Guest::class);
});

test('tenant has many bookings', function () {
    $guest = Guest::factory()->create();

    // Create multiple bookings using the event sourcing action
    for ($i = 0; $i < 3; $i++) {
        (new CreateBookingAction)->execute(
            guestUuid: $guest->id,
            startDate: '2025-08-01',
            endDate: '2025-08-07',
            tenantUuid: $this->tenant->id
        );
    }

    expect($this->tenant->bookings)->toHaveCount(3);
});

test('guest has many bookings', function () {
    $guest = Guest::factory()->create();

    // Create multiple bookings using the event sourcing action
    for ($i = 0; $i < 2; $i++) {
        (new CreateBookingAction)->execute(
            guestUuid: $guest->id,
            startDate: '2025-08-01',
            endDate: '2025-08-07'
        );
    }

    expect($guest->bookings)->toHaveCount(2);
});
