<?php

namespace App\Domain\Booking\Actions;

use App\Domain\Booking\Aggregates\BookingAggregateRoot;

class ChangeBookingDatesAction
{
    /**
     * Change the dates of a booking.
     *
     * @param  string  $bookingUuid  The UUID of the booking to update
     * @param  string  $startDate  The new start date (Y-m-d format)
     * @param  string  $endDate  The new end date (Y-m-d format)
     */
    public function execute(
        string $bookingUuid,
        string $startDate,
        string $endDate
    ): void {
        // Change the booking dates through the aggregate
        BookingAggregateRoot::retrieve($bookingUuid)
            ->changeDates(
                startDate: $startDate,
                endDate: $endDate,
            )
            ->persist();
    }
}
