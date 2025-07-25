<?php

namespace App\Providers;

use App\Domain\Booking\Projectors\BookingProjector;
use Illuminate\Support\ServiceProvider;
use Spatie\EventSourcing\Facades\Projectionist;

class BookingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register the booking projector
        Projectionist::addProjector(BookingProjector::class);
    }
}
