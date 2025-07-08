<?php

use App\Models\Tenant;
use App\Settings\TenantSettings;
use App\SettingsRepositories\TenantDatabaseSettingsRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Spatie\LaravelSettings\SettingsContainer;

uses(RefreshDatabase::class);

test('tenant settings can be retrieved', function () {
    // Create a tenant
    $tenant = Tenant::factory()->create([
        'name' => 'Test Campsite',
    ]);

    // Create a repository with the tenant ID
    $repository = new TenantDatabaseSettingsRepository(config('settings.repositories.tenant_database'));
    $repository->setTenantId($tenant->id);

    // Bind the repository to the container
    App::instance('settings.repository.tenant_database', $repository);

    // Get the settings
    $settings = app(TenantSettings::class);

    // Check that the settings have the default values
    expect($settings->site_name)->toBe('Campmaster')
        ->and($settings->contact_email)->toBe('info@example.com')
        ->and($settings->phone_number)->toBe('+31 123 456 789')
        ->and($settings->booking_enabled)->toBeTrue()
        ->and($settings->max_guests_per_booking)->toBe(10);
});

test('tenant settings can be updated', function () {
    // Create a tenant
    $tenant = Tenant::factory()->create([
        'name' => 'Test Campsite',
    ]);

    // Create a repository with the tenant ID
    $repository = new TenantDatabaseSettingsRepository(config('settings.repositories.tenant_database'));
    $repository->setTenantId($tenant->id);

    // Bind the repository to the container
    App::instance('settings.repository.tenant_database', $repository);

    // Get the settings
    $settings = app(TenantSettings::class);

    // Update the settings
    $settings->site_name = 'Updated Site Name';
    $settings->contact_email = 'updated@example.com';
    $settings->booking_enabled = false;
    $settings->max_guests_per_booking = 5;
    $settings->save();

    // Get the settings again to verify they were updated
    $newSettings = app(TenantSettings::class);

    // Check that the settings have the updated values
    expect($newSettings->site_name)->toBe('Updated Site Name')
        ->and($newSettings->contact_email)->toBe('updated@example.com')
        ->and($newSettings->booking_enabled)->toBeFalse()
        ->and($newSettings->max_guests_per_booking)->toBe(5);
});

test('settings are unique per tenant', function () {
    // Create two tenants
    $tenant1 = Tenant::factory()->create([
        'name' => 'First Campsite',
    ]);

    $tenant2 = Tenant::factory()->create([
        'name' => 'Second Campsite',
    ]);

    // Create a repository for the first tenant
    $repository1 = new TenantDatabaseSettingsRepository(config('settings.repositories.tenant_database'));
    $repository1->setTenantId($tenant1->id);

    // Set up settings for the first tenant directly using the repository
    $repository1->updatePropertiesPayload('tenant', [
        'site_name' => 'First Campsite Settings',
        'contact_email' => 'first@example.com',
    ]);

    // Create a repository for the second tenant
    $repository2 = new TenantDatabaseSettingsRepository(config('settings.repositories.tenant_database'));
    $repository2->setTenantId($tenant2->id);

    // Set up settings for the second tenant directly using the repository
    $repository2->updatePropertiesPayload('tenant', [
        'site_name' => 'Second Campsite Settings',
        'contact_email' => 'second@example.com',
    ]);

    // Verify settings for the first tenant directly using the repository
    $firstTenantSettings = $repository1->getPropertiesInGroup('tenant');
    expect($firstTenantSettings['site_name'])->toBe('First Campsite Settings')
        ->and($firstTenantSettings['contact_email'])->toBe('first@example.com');

    // Verify settings for the second tenant directly using the repository
    $secondTenantSettings = $repository2->getPropertiesInGroup('tenant');
    expect($secondTenantSettings['site_name'])->toBe('Second Campsite Settings')
        ->and($secondTenantSettings['contact_email'])->toBe('second@example.com');
});
