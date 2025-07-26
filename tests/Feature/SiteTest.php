<?php

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Site;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a tenant for testing
    $this->tenant = Tenant::create([
        'id' => Str::uuid()->toString(),
        'name' => 'Test Campsite',
        'domain' => 'testcampsite',
    ]);

    // Make this tenant the current tenant using Spatie's method
    $this->tenant->makeCurrent();
});

test('can create a site', function () {
    $site = Site::create([
        'id' => Str::uuid()->toString(),
        'tenant_id' => $this->tenant->id,
        'name' => 'Test Site',
        'description' => 'A test campsite',
    ]);

    expect($site)->toBeInstanceOf(Site::class)
        ->and($site->name)->toBe('Test Site')
        ->and($site->description)->toBe('A test campsite')
        ->and($site->tenant_id)->toBe($this->tenant->id);
});

test('can retrieve a site', function () {
    $siteId = Str::uuid()->toString();

    Site::create([
        'id' => $siteId,
        'tenant_id' => $this->tenant->id,
        'name' => 'Test Site',
        'description' => 'A test campsite',
    ]);

    $site = Site::find($siteId);

    expect($site)->toBeInstanceOf(Site::class)
        ->and($site->name)->toBe('Test Site')
        ->and($site->description)->toBe('A test campsite');
});

test('can update a site', function () {
    $site = Site::create([
        'id' => Str::uuid()->toString(),
        'tenant_id' => $this->tenant->id,
        'name' => 'Test Site',
        'description' => 'A test campsite',
    ]);

    $site->update([
        'name' => 'Updated Site',
        'description' => 'An updated test campsite',
    ]);

    $site->refresh();

    expect($site->name)->toBe('Updated Site')
        ->and($site->description)->toBe('An updated test campsite');
});

test('can delete a site', function () {
    $site = Site::create([
        'id' => Str::uuid()->toString(),
        'tenant_id' => $this->tenant->id,
        'name' => 'Test Site',
        'description' => 'A test campsite',
    ]);

    $siteId = $site->id;

    $site->delete();

    expect(Site::find($siteId))->toBeNull();
});

test('sites are scoped to tenant', function () {
    // Create a site for the current tenant
    Site::create([
        'id' => Str::uuid()->toString(),
        'tenant_id' => $this->tenant->id,
        'name' => 'Current Tenant Site',
        'description' => 'A site for the current tenant',
    ]);

    // Create another tenant
    $anotherTenant = Tenant::create([
        'id' => Str::uuid()->toString(),
        'name' => 'Another Campsite',
        'domain' => 'anothercampsite',
    ]);

    // Create a site for the other tenant
    Site::create([
        'id' => Str::uuid()->toString(),
        'tenant_id' => $anotherTenant->id,
        'name' => 'Other Tenant Site',
        'description' => 'A site for another tenant',
    ]);

    // We should only see sites for the current tenant
    $sites = Site::all();

    expect($sites)->toHaveCount(1)
        ->and($sites->first()->name)->toBe('Current Tenant Site');
});

test('site has bookings relationship', function () {
    $site = Site::create([
        'id' => Str::uuid()->toString(),
        'tenant_id' => $this->tenant->id,
        'name' => 'Test Site',
        'description' => 'A test campsite',
    ]);

    // Create a guest for the booking
    $guest = Guest::create([
        'id' => Str::uuid()->toString(),
        'tenant_id' => $this->tenant->id,
        'firstname' => 'Test',
        'lastname' => 'Guest',
        'email' => 'test@example.com',
        'street' => 'Test Street',
        'house_number' => '123',
        'postal_code' => '12345',
        'city' => 'Test City',
        'country' => 'Test Country',
    ]);

    // Create a booking associated with the site
    $booking = Booking::create([
        'id' => Str::uuid()->toString(),
        'tenant_id' => $this->tenant->id,
        'guest_id' => $guest->id,
        'site_id' => $site->id,
        'status' => BookingStatus::Pending->value,
        'start_date' => now()->addDay(),
        'end_date' => now()->addDays(3),
    ]);

    expect($site->bookings)->toHaveCount(1)
        ->and($site->bookings->first()->id)->toBe($booking->id);
});
