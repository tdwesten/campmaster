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
