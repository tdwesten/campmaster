<?php

use App\Models\Tenant;
use App\Settings\Repositories\TenantDatabaseSettingsRepository;
use Illuminate\Support\Facades\DB;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('tenant_id is saved to the database when creating a setting', function () {
    // Create a tenant
    $tenant = Tenant::factory()->create([
        'name' => 'Test Tenant',
    ]);

    // Make the tenant current
    $tenant->makeCurrent();

    // Get the settings repository
    $repository = app(TenantDatabaseSettingsRepository::class, [
        'config' => config('settings.repositories.database'),
    ]);

    // Create a setting for the tenant
    $repository->createProperty('tenant', 'test_setting', 'Test Value');

    // Query the database directly to check if tenant_id was saved
    $setting = DB::table('settings')
        ->where('group', 'tenant')
        ->where('name', 'test_setting')
        ->first();

    // Verify that the setting exists and has the correct tenant_id
    expect($setting)->not->toBeNull();
    expect($setting->tenant_id)->toBe($tenant->id);
});
