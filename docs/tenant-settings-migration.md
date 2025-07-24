# Tenant-Specific Settings Migrations

This document explains how to use the Spatie Laravel Settings package's `SettingsMigration` tool with our tenant-aware
extensions.

## Overview

The Campmaster application uses Spatie's Laravel Settings package with a custom tenant-aware repository. This allows
each tenant to have its own settings while still supporting global settings that apply to all tenants.

When creating settings migrations, you need to be aware of the tenant context to ensure that settings are created for
the correct tenant.

## Creating Tenant-Specific Settings

To create tenant-specific settings in a migration, you need to:

1. Make each tenant current before adding settings for that tenant
2. Forget the current tenant after processing each tenant

Here's an example of a settings migration that creates tenant-specific settings:

```php
<?php

use App\Models\Tenant;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Make sure no tenant is current at the start
        Tenant::forgetCurrent();
        
        // Process each tenant
        Tenant::all()->each(function ($tenant) {
            // Make this tenant current so settings are created with the correct tenant_id
            $tenant->makeCurrent();
            
            // Add tenant-specific settings
            $this->migrator->add('tenant.site_name', 'Campmaster');
            $this->migrator->add('tenant.contact_email', 'info@example.com');
            // Add more tenant-specific settings...
            
            // Forget the current tenant before moving to the next one
            Tenant::forgetCurrent();
        });
    }
};
```

## Creating Global Settings

To create global settings that apply to all tenants, ensure no tenant is current:

```php
<?php

use App\Models\Tenant;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Make sure no tenant is current
        Tenant::forgetCurrent();
        
        // Add global settings (no tenant context)
        $this->migrator->add('global.app_name', 'Campmaster');
        $this->migrator->add('global.app_version', '1.0.0');
        // Add more global settings...
    }
};
```

## How It Works

The key to making settings migrations tenant-aware is the tenant context. When a tenant is made current using
`$tenant->makeCurrent()`, our custom `TenantDatabaseSettingsRepository` includes the tenant's ID when creating settings.

The repository's `createProperty` method includes the current tenant ID:

```php
public function createProperty(string $group, string $name, $payload): void
{
    $this->getBuilder()->create([
        'group' => $group,
        'name' => $name,
        'tenant_id' => $this->getCurrentTenantId(), // Include tenant ID
        'payload' => $this->encode($payload),
        'locked' => false,
    ]);
}
```

When no tenant is current, `$this->getCurrentTenantId()` returns `null`, creating a global setting.

## Testing

You can test your settings migrations to ensure they create settings with the correct tenant context:

```php
test('settings migration creates tenant-specific settings', function () {
    // Create tenants
    $tenant1 = Tenant::factory()->create(['name' => 'Tenant One']);
    $tenant2 = Tenant::factory()->create(['name' => 'Tenant Two']);
    
    // Run your settings migration
    // ...
    
    // Make the first tenant current
    $tenant1->makeCurrent();
    
    // Verify that the first tenant has its own settings
    $repository = app(DatabaseSettingsRepository::class);
    $properties = $repository->getPropertiesInGroup('tenant');
    expect($properties['site_name'])->toBe('Tenant One Site');
    
    // Make the second tenant current
    $tenant1->forget();
    $tenant2->makeCurrent();
    
    // Verify that the second tenant has its own settings
    $properties = $repository->getPropertiesInGroup('tenant');
    expect($properties['site_name'])->toBe('Tenant Two Site');
});
```

## Best Practices

1. Always start your migration by calling `Tenant::forgetCurrent()` to ensure no tenant is current
2. For tenant-specific settings, iterate through all tenants and make each one current before adding settings
3. For global settings, ensure no tenant is current by calling `Tenant::forgetCurrent()`
4. Always forget the current tenant after processing each tenant to avoid leaking tenant context
5. Use descriptive setting names that clearly indicate whether they are tenant-specific or global

## Conclusion

By following these guidelines, you can create settings migrations that properly handle tenant-specific and global
settings in the Campmaster application.
