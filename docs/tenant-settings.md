# Tenant-Specific Settings

This document explains how tenant-specific settings are implemented in the Campmaster application.

## Overview

The application uses [Spatie's Laravel Settings package](https://github.com/spatie/laravel-settings) to manage settings,
extended with tenant-specific functionality. This allows each tenant to have its own settings while still supporting
global settings that apply to all tenants.

## Implementation Details

### Database Schema

The settings table has been extended with a `tenant_id` column that references the `tenants` table:

```php
Schema::table('settings', function (Blueprint $table) {
    // Add tenant_id column (nullable to support both tenant-specific and global settings)
    $table->uuid('tenant_id')->nullable()->after('id');
    
    // Add foreign key constraint
    $table->foreign('tenant_id')
        ->references('id')
        ->on('tenants')
        ->onDelete('cascade');
    
    // Update unique constraint to include tenant_id
    $table->dropUnique(['group', 'name']);
    $table->unique(['tenant_id', 'group', 'name']);
});
```

The `tenant_id` column is nullable to support global settings (where `tenant_id` is `null`).

### Custom Repository

A custom repository class `TenantDatabaseSettingsRepository` extends the default `DatabaseSettingsRepository` to include
tenant filtering in queries:

```php
namespace App\Settings\Repositories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository;

class TenantDatabaseSettingsRepository extends DatabaseSettingsRepository
{
    protected function getCurrentTenantId(): ?string
    {
        $tenant = Tenant::current();
        
        return $tenant?->id;
    }

    public function getBuilder(): Builder
    {
        $builder = parent::getBuilder();
        
        $tenantId = $this->getCurrentTenantId();
        
        // If we have a current tenant, filter by it or allow null tenant_id (global settings)
        if ($tenantId !== null) {
            $builder->where(function (Builder $query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)
                      ->orWhereNull('tenant_id');
            });
        }
        
        return $builder;
    }

    public function createProperty(string $group, string $name, $payload): void
    {
        $this->getBuilder()->create([
            'group' => $group,
            'name' => $name,
            'tenant_id' => $this->getCurrentTenantId(),
            'payload' => $this->encode($payload),
            'locked' => false,
        ]);
    }

    public function updatePropertiesPayload(string $group, array $properties): void
    {
        $tenantId = $this->getCurrentTenantId();
        
        $propertiesInBatch = collect($properties)->map(function ($payload, $name) use ($group, $tenantId) {
            return [
                'group' => $group,
                'name' => $name,
                'tenant_id' => $tenantId,
                'payload' => $this->encode($payload),
            ];
        })->values()->toArray();

        // For tenant-specific settings, we need to include tenant_id in the unique constraint
        if ($tenantId !== null) {
            $this->getBuilder()
                ->where('group', $group)
                ->upsert($propertiesInBatch, ['group', 'name', 'tenant_id'], ['payload']);
        } else {
            // For global settings (no tenant), use the default behavior
            parent::updatePropertiesPayload($group, $properties);
        }
    }
}
```

This repository:

1. Gets the current tenant ID using `Tenant::current()`
2. Overrides `getBuilder()` to filter queries by the current tenant ID or allow null tenant_id (global settings)
3. Overrides `createProperty()` to include the tenant_id when creating a new setting
4. Overrides `updatePropertiesPayload()` to handle tenant-specific settings differently from global settings

### Configuration

The custom repository is configured as the default repository in `config/settings.php`:

```php
'repositories' => [
    'database' => [
        'type' => App\Settings\Repositories\TenantDatabaseSettingsRepository::class,
        'model' => null,
        'table' => null,
        'connection' => null,
    ],
    // ...
],
```

## Usage

### Tenant-Specific Settings

When a tenant is current (using `$tenant->makeCurrent()`), any settings created or retrieved will be scoped to that
tenant:

```php
// Make a tenant current
$tenant = Tenant::find($tenantId);
$tenant->makeCurrent();

// Create or update a setting for the current tenant
$repository->createProperty('tenant', 'site_name', 'My Campsite');

// Retrieve the setting for the current tenant
$properties = $repository->getPropertiesInGroup('tenant');
$siteName = $properties['site_name']; // 'My Campsite'
```

### Global Settings

To create global settings that apply to all tenants, ensure no tenant is current:

```php
// Forget the current tenant
Tenant::forgetCurrent();

// Create or update a global setting
$repository->createProperty('global', 'app_name', 'Campmaster');
```

Global settings are visible to all tenants, alongside their tenant-specific settings.

## Testing

The implementation includes tests to verify that settings are properly scoped to tenants:

1. `tenant settings are scoped to the current tenant` - Verifies that each tenant can have its own setting with the same
   name but different values
2. `global settings are visible to all tenants` - Verifies that settings without a tenant_id (global settings) are
   visible to all tenants
3. `updating tenant settings only affects the current tenant` - Verifies that updating a setting for one tenant doesn't
   affect the same setting for another tenant

Run the tests with:

```bash
php artisan test tests/Feature/Settings/TenantSettingsTest.php
```
