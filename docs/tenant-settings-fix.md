# Tenant Settings Fix

This document explains the fix for the issue where the `tenant_id` was not being saved to the database when creating
settings.

## Issue

The issue was that the `tenant_id` column was not being saved to the database when creating settings, even though:

1. The `tenant_id` column existed in the database
2. The `tenant_id` was in the model's `$fillable` array
3. The custom `TenantDatabaseSettingsRepository` class was correctly setting the `tenant_id` when creating settings

## Root Cause

The root cause of the issue was that some parts of the application were directly requesting the Spatie
`DatabaseSettingsRepository` class from the container instead of using the interface `SettingsRepository` or our custom
`TenantDatabaseSettingsRepository` class.

When the application requested the Spatie repository class directly, it bypassed our custom repository class, which is
responsible for setting the `tenant_id` when creating settings.

## Solution

The solution was to create a service provider that binds the Spatie `DatabaseSettingsRepository` class to our custom
`TenantDatabaseSettingsRepository` class. This ensures that even if code directly requests the Spatie repository class,
it will get our custom class instead.

### 1. Create a Service Provider

We created a new service provider `SettingsServiceProvider` that binds the Spatie repository class to our custom
repository class:

```php
<?php

namespace App\Providers;

use App\Settings\Repositories\TenantDatabaseSettingsRepository;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the Spatie DatabaseSettingsRepository to our custom TenantDatabaseSettingsRepository
        // This ensures that even if code directly requests the Spatie repository class,
        // it will get our custom class instead
        $this->app->bind(DatabaseSettingsRepository::class, function ($app) {
            return $app->make(TenantDatabaseSettingsRepository::class, [
                'config' => config('settings.repositories.database'),
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
```

### 2. Register the Service Provider

We registered the service provider in the `bootstrap/providers.php` file:

```php
<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\SettingsServiceProvider::class,
];
```

## Best Practices

To avoid similar issues in the future, follow these best practices:

1. **Use interfaces instead of concrete classes**: When requesting a class from the container, use the interface instead
   of the concrete class. This allows for easier substitution of implementations.

2. **Use the factory when available**: The Spatie package provides a factory for creating repositories. Use this factory
   instead of directly requesting the repository class from the container.

3. **Write tests that verify the behavior**: Write tests that verify that the `tenant_id` is being saved to the database
   when creating settings. This will catch similar issues in the future.

## Conclusion

By binding the Spatie repository class to our custom repository class, we ensure that all code in the application uses
our tenant-aware repository, even if it directly requests the Spatie repository class. This fixes the issue where the
`tenant_id` was not being saved to the database when creating settings.
