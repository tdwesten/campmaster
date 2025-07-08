<?php

namespace App\Providers;

use App\Models\Tenant;
use App\Settings\TenantSettings;
use App\SettingsRepositories\TenantDatabaseSettingsRepository;
use Illuminate\Support\ServiceProvider;

class TenantSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register our custom settings repository
        $this->app->bind('settings.repository.tenant_database', function ($app) {
            $config = config('settings.repositories.tenant_database');
            $repository = new TenantDatabaseSettingsRepository($config);

            // Get the current tenant
            $tenant = Tenant::current();

            // Set the tenant ID in the repository
            if ($tenant) {
                $repository->setTenantId($tenant->id);
            }

            return $repository;
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
