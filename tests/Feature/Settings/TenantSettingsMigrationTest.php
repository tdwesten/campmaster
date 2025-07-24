<?php

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;
use Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository;

test('settings migration creates tenant-specific settings', function () {
    // Create two tenants
    $tenant1 = Tenant::factory()->create([
        'name' => 'Tenant One',
    ]);

    $tenant2 = Tenant::factory()->create([
        'name' => 'Tenant Two',
    ]);

    // Create a test settings migration
    $migration = new class extends SettingsMigration
    {
        public function up(): void
        {
            // Make sure no tenant is current at the start
            Tenant::forgetCurrent();

            // Process each tenant
            Tenant::all()->each(function ($tenant) {
                // Make this tenant current so settings are created with the correct tenant_id
                $tenant->makeCurrent();

                // Add a test setting
                $this->migrator->add('test.setting', 'Test Value for '.$tenant->name);

                // Forget the current tenant before moving to the next one
                Tenant::forgetCurrent();
            });
        }
    };

    // Run the migration
    $migration->up();

    // Get the settings repository
    $repository = app(DatabaseSettingsRepository::class, [
        'config' => config('settings.repositories.database'),
    ]);

    // Make the first tenant current
    $tenant1->makeCurrent();

    // Verify that the first tenant has its own setting
    $properties = $repository->getPropertiesInGroup('test');

    expect($properties)->toHaveKey('setting');
    expect($properties['setting'])->toBe('Test Value for Tenant One');

    // Make the second tenant current
    $tenant1->forget();
    $tenant2->makeCurrent();

    // Verify that the second tenant has its own setting
    $properties = $repository->getPropertiesInGroup('test');
    expect($properties)->toHaveKey('setting');
    expect($properties['setting'])->toBe('Test Value for Tenant Two');

    // Verify in the database that each setting has the correct tenant_id
    $settings = DB::table('settings')
        ->where('group', 'test')
        ->where('name', 'setting')
        ->get();

    expect($settings)->toHaveCount(2);

    $tenantIds = $settings->pluck('tenant_id')->toArray();
    expect($tenantIds)->toContain($tenant1->id);
    expect($tenantIds)->toContain($tenant2->id);
});

test('settings migration can create global settings', function () {
    // Create two tenants
    $tenant1 = Tenant::factory()->create([
        'name' => 'Tenant One',
    ]);

    $tenant2 = Tenant::factory()->create([
        'name' => 'Tenant Two',
    ]);

    // Create a test settings migration for global settings
    $migration = new class extends SettingsMigration
    {
        public function up(): void
        {
            // Make sure no tenant is current
            Tenant::forgetCurrent();

            // Add a global setting (no tenant context)
            $this->migrator->add('global.setting', 'Global Value');
        }
    };

    // Run the migration
    $migration->up();

    // Get the settings repository
    $repository = app(DatabaseSettingsRepository::class, [
        'config' => config('settings.repositories.database'),
    ]);

    // Make the first tenant current
    $tenant1->makeCurrent();

    // Verify that the first tenant can see the global setting
    $properties = $repository->getPropertiesInGroup('global');
    expect($properties)->toHaveKey('setting');
    expect($properties['setting'])->toBe('Global Value');

    // Make the second tenant current
    $tenant1->forget();
    $tenant2->makeCurrent();

    // Verify that the second tenant can also see the global setting
    $properties = $repository->getPropertiesInGroup('global');
    expect($properties)->toHaveKey('setting');
    expect($properties['setting'])->toBe('Global Value');

    // Verify in the database that the global setting has a null tenant_id
    $settings = DB::table('settings')
        ->where('group', 'global')
        ->where('name', 'setting')
        ->get();

    expect($settings)->toHaveCount(1);
    expect($settings[0]->tenant_id)->toBeNull();
});
