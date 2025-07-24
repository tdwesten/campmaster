<?php

use App\Models\Tenant;
use Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Create a tenant
    $this->tenant = Tenant::factory()->create([
        'name' => 'Camping De Nachtegaal',
    ]);

    $this->tenant->makeCurrent();
});

test('tenant settings are scoped to the current tenant', function () {
    // Create two tenants
    $tenant1 = Tenant::factory()->create([
        'name' => 'Tenant One',
    ]);

    $tenant2 = Tenant::factory()->create([
        'name' => 'Tenant Two',
    ]);

    // Get the settings repository
    $repository = app(DatabaseSettingsRepository::class, [
        'config' => config('settings.repositories.database'),
    ]);

    // Make the first tenant current
    $tenant1->makeCurrent();

    // Create a setting for the first tenant
    $repository->createProperty('tenant', 'site_name', 'Tenant One Site');

    // Make the second tenant current
    $tenant1->forget();

    $tenant2->makeCurrent();

    // Create a setting with the same name for the second tenant
    $repository->createProperty('tenant', 'site_name', 'Tenant Two Site');

    // Verify that the second tenant sees its own setting
    $properties = $repository->getPropertiesInGroup('tenant');
    expect($properties)->toHaveKey('site_name');
    expect($properties['site_name'])->toBe('Tenant Two Site');

    // Switch back to the first tenant
    $tenant2->forget();
    $tenant1->makeCurrent();

    // Verify that the first tenant sees its own setting
    $properties = $repository->getPropertiesInGroup('tenant');
    expect($properties)->toHaveKey('site_name');
    expect($properties['site_name'])->toBe('Tenant One Site');
});

test('global settings are visible to all tenants', function () {
    // Create two tenants
    $tenant1 = Tenant::factory()->create([
        'name' => 'Tenant One',
    ]);

    $tenant2 = Tenant::factory()->create([
        'name' => 'Tenant Two',
    ]);

    // Get the settings repository
    $repository = app(DatabaseSettingsRepository::class, [
        'config' => config('settings.repositories.database'),
    ]);

    // Create a global setting (without making any tenant current)
    Tenant::forgetCurrent();
    $repository->createProperty('global', 'app_name', 'Campmaster');

    // Make the first tenant current
    $tenant1->makeCurrent();

    // Verify that the first tenant can see the global setting
    $properties = $repository->getPropertiesInGroup('global');
    expect($properties)->toHaveKey('app_name');
    expect($properties['app_name'])->toBe('Campmaster');

    // Create a tenant-specific setting
    $repository->createProperty('tenant', 'site_name', 'Tenant One Site');

    // Make the second tenant current
    $tenant1->forget();
    $tenant2->makeCurrent();

    // Verify that the second tenant can see the global setting
    $properties = $repository->getPropertiesInGroup('global');
    expect($properties)->toHaveKey('app_name');
    expect($properties['app_name'])->toBe('Campmaster');

    // But cannot see the first tenant's setting
    $properties = $repository->getPropertiesInGroup('tenant');
    if (isset($properties['site_name'])) {
        expect($properties['site_name'])->not->toBe('Tenant One Site');
    }
});

test('updating tenant settings only affects the current tenant', function () {
    // Create two tenants
    $tenant1 = Tenant::factory()->create([
        'name' => 'Tenant One',
    ]);

    $tenant2 = Tenant::factory()->create([
        'name' => 'Tenant Two',
    ]);

    // Get the settings repository
    $repository = app(DatabaseSettingsRepository::class, [
        'config' => config('settings.repositories.database'),
    ]);

    // Make the first tenant current
    $tenant1->makeCurrent();

    // Create a setting for the first tenant
    $repository->createProperty('tenant', 'site_name', 'Tenant One Site');

    // Make the second tenant current
    $tenant1->forget();
    $tenant2->makeCurrent();

    // Create a setting with the same name for the second tenant
    $repository->createProperty('tenant', 'site_name', 'Tenant Two Site');

    // Update the setting for the second tenant
    $repository->updatePropertiesPayload('tenant', [
        'site_name' => 'Tenant Two Site Updated',
    ]);

    // Verify that the second tenant's setting was updated
    $properties = $repository->getPropertiesInGroup('tenant');
    expect($properties)->toHaveKey('site_name');
    expect($properties['site_name'])->toBe('Tenant Two Site Updated');

    // Switch back to the first tenant
    $tenant2->forget();
    $tenant1->makeCurrent();

    // Verify that the first tenant's setting was not affected
    $properties = $repository->getPropertiesInGroup('tenant');
    expect($properties)->toHaveKey('site_name');
    expect($properties['site_name'])->toBe('Tenant One Site');
});
