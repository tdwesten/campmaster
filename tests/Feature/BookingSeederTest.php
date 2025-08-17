<?php

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Site;
use App\Models\Tenant;
use Database\Seeders\BookingSeeder;
use Database\Seeders\GuestSeeder;
use Database\Seeders\SiteSeeder;
use Database\Seeders\TenantSeeder;

it('seeds bookings (reservations) per tenant with valid relations', function () {
    // Run seeders in proper order
    (new TenantSeeder)->run();
    (new SiteSeeder)->run();
    (new GuestSeeder)->run();
    (new BookingSeeder)->run();

    // We expect exactly 1 tenant created by TenantSeeder
    $tenant = Tenant::first();
    expect($tenant)->not->toBeNull();

    // BookingSeeder creates 35 bookings per tenant by default
    expect(Booking::count())->toBe(35);

    // All bookings belong to the same tenant
    expect(Booking::query()->pluck('tenant_id')->unique()->all())
        ->toBe([$tenant->id]);

    // Ensure bookings reference existing guest and site from the same tenant
    $booking = Booking::first();
    expect($booking)->not->toBeNull();

    $guest = Guest::find($booking->guest_id);
    $site = Site::find($booking->site_id);

    expect($guest)->not->toBeNull()
        ->and($site)->not->toBeNull()
        ->and($guest->tenant_id)->toBe($tenant->id)
        ->and($site->tenant_id)->toBe($tenant->id);
});
