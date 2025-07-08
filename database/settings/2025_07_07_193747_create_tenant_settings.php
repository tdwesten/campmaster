<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('tenant.site_name', 'Campmaster');
        $this->migrator->add('tenant.contact_email', 'info@example.com');
        $this->migrator->add('tenant.phone_number', '+31 123 456 789');
        $this->migrator->add('tenant.address', '');
        $this->migrator->add('tenant.logo_path', '');
        $this->migrator->add('tenant.colors', [
            'primary' => '#4f46e5',
            'secondary' => '#0ea5e9',
            'accent' => '#f97316',
        ]);
        $this->migrator->add('tenant.booking_enabled', true);
        $this->migrator->add('tenant.max_guests_per_booking', 10);
    }
};
