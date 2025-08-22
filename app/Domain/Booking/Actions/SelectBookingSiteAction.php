<?php

namespace App\Domain\Booking\Actions;

use App\Domain\Booking\Aggregates\BookingAggregateRoot;

class SelectBookingSiteAction
{
    /**
     * Select a site for a booking.
     *
     * @param  string  $bookingUuid  The UUID of the booking
     * @param  string|null  $siteUuid  The UUID of the site to select (nullable to clear selection)
     */
    public function execute(string $bookingUuid, ?string $siteUuid): void
    {
        BookingAggregateRoot::retrieve($bookingUuid)
            ->selectSite($siteUuid)
            ->persist();
    }
}
