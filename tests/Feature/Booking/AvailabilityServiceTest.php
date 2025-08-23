<?php

use App\Domain\Booking\Services\AvailabilityService;
use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Tenant;
use App\Settings\BookingSettings;
use Carbon\Carbon;
use Database\Factories\SiteFactory;

it('allows back-to-back bookings when offset is 0', function () {
    // Arrange
    $tenant = Tenant::factory()->create();
    $tenant->makeCurrent();

    $site = SiteFactory::new()->create();

    // Existing booking: 2025-06-10 to 2025-06-15
    Booking::factory()->create([
        'site_id' => $site->id,
        'status' => BookingStatus::Confirmed,
        'start_date' => Carbon::create(2025, 6, 10),
        'end_date' => Carbon::create(2025, 6, 15),
    ]);

    // Set settings offset to 0
    $settings = app(BookingSettings::class);
    $settings->offset_between_bookings_days = 0;
    $settings->save();

    $service = app(AvailabilityService::class);

    // Act & Assert
    // Back-to-back starting on end date should be available
    $available = $service->isSiteAvailable(
        $site->id,
        Carbon::create(2025, 6, 15),
        Carbon::create(2025, 6, 20)
    );

    expect($available)->toBeTrue();

    // Overlapping by one day (14-16) must be unavailable
    $unavailable = $service->isSiteAvailable(
        $site->id,
        Carbon::create(2025, 6, 14),
        Carbon::create(2025, 6, 16)
    );

    expect($unavailable)->toBeFalse();
});

it('requires gap between bookings according to offset setting', function () {
    // Arrange
    $tenant = Tenant::factory()->create();
    $tenant->makeCurrent();

    $site = SiteFactory::new()->create();

    // Existing booking: 2025-06-10 to 2025-06-15
    Booking::factory()->create([
        'site_id' => $site->id,
        'status' => BookingStatus::Confirmed,
        'start_date' => Carbon::create(2025, 6, 10),
        'end_date' => Carbon::create(2025, 6, 15),
    ]);

    // Set settings offset to 1 day
    $settings = app(BookingSettings::class);
    $settings->offset_between_bookings_days = 1;
    $settings->save();

    $service = app(AvailabilityService::class);

    // Act & Assert
    // Starting on 15th is NOT allowed because we need 1-day gap
    $unavailable = $service->isSiteAvailable(
        $site->id,
        Carbon::create(2025, 6, 15),
        Carbon::create(2025, 6, 20)
    );
    expect($unavailable)->toBeFalse();

    // Starting on 16th IS allowed (one-day gap after 15th)
    $available = $service->isSiteAvailable(
        $site->id,
        Carbon::create(2025, 6, 16),
        Carbon::create(2025, 6, 20)
    );
    expect($available)->toBeTrue();
});

it('ignores cancelled bookings for availability', function () {
    // Arrange
    $tenant = Tenant::factory()->create();
    $tenant->makeCurrent();

    $site = SiteFactory::new()->create();

    Booking::factory()->create([
        'site_id' => $site->id,
        'status' => BookingStatus::Cancelled,
        'start_date' => Carbon::create(2025, 6, 10),
        'end_date' => Carbon::create(2025, 6, 15),
    ]);

    $settings = app(BookingSettings::class);
    $settings->offset_between_bookings_days = 2;
    $settings->save();

    $service = app(AvailabilityService::class);

    // This would conflict if not cancelled
    $available = $service->isSiteAvailable(
        $site->id,
        Carbon::create(2025, 6, 14),
        Carbon::create(2025, 6, 16)
    );

    expect($available)->toBeTrue();
});

it('returns available sites for a date range', function () {
    // Arrange
    $tenant = Tenant::factory()->create();
    $tenant->makeCurrent();

    $site1 = SiteFactory::new()->create();
    $site2 = SiteFactory::new()->create();

    // Site 1 has a booking 10-15 June
    Booking::factory()->create([
        'site_id' => $site1->id,
        'status' => BookingStatus::Confirmed,
        'start_date' => Carbon::create(2025, 6, 10),
        'end_date' => Carbon::create(2025, 6, 15),
    ]);

    // Offset 0
    $settings = app(BookingSettings::class);
    $settings->offset_between_bookings_days = 0;
    $settings->save();

    $service = app(AvailabilityService::class);

    // Request 15-18 June, site1 is available back-to-back; site2 is also available
    $available = $service->availableSites(
        Carbon::create(2025, 6, 15),
        Carbon::create(2025, 6, 18)
    );

    expect($available->pluck('id')->all())
        ->toContain($site1->id)
        ->toContain($site2->id);

    // With offset 2, site1 is not available for 15-18, site2 remains available
    $availableWithOffset = $service->availableSites(
        Carbon::create(2025, 6, 15),
        Carbon::create(2025, 6, 18),
        [],
        2
    );

    expect($availableWithOffset->pluck('id')->all())
        ->not->toContain($site1->id)
        ->toContain($site2->id);
});
