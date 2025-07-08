<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class TenantSettings extends Settings
{
    public string $site_name;
    public string $contact_email;
    public string $phone_number;
    public string $address;
    public string $logo_path;
    public array $colors;
    public bool $booking_enabled;
    public int $max_guests_per_booking;

    public static function group(): string
    {
        return 'tenant';
    }

    public static function repository(): ?string
    {
        return 'tenant_database';
    }

    public static function encrypted(): array
    {
        // Disable encryption for tests
        if (app()->environment('testing')) {
            return [];
        }

        return [
            'contact_email',
            'phone_number',
        ];
    }

    public static function default(): array
    {
        return [
            'site_name' => 'Campmaster',
            'contact_email' => 'info@example.com',
            'phone_number' => '+31 123 456 789',
            'address' => '',
            'logo_path' => '',
            'colors' => [
                'primary' => '#4f46e5',
                'secondary' => '#0ea5e9',
                'accent' => '#f97316',
            ],
            'booking_enabled' => true,
            'max_guests_per_booking' => 10,
        ];
    }
}
