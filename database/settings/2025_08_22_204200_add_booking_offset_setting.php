<?php

use App\Models\Tenant;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Ensure we seed a default for all existing tenants and also the global scope
        Tenant::forgetCurrent();

        // Global default (no tenant) just in case code reads settings without a current tenant
        $this->migrator->add('booking.offset_between_bookings_days', 0);

        // Per-tenant defaults
        Tenant::all()->each(function ($tenant) {
            $tenant->makeCurrent();
            $this->migrator->add('booking.offset_between_bookings_days', 0);
            Tenant::forgetCurrent();
        });
    }
};
