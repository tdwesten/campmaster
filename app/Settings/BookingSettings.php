<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class BookingSettings extends Settings
{
    /**
     * Number of empty days required between two bookings on the same site.
     * 0 allows back-to-back bookings (checkout and next check-in on the same day).
     */
    public int $offset_between_bookings_days;

    public static function group(): string
    {
        return 'booking';
    }
}
