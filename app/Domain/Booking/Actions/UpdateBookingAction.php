<?php

namespace App\Domain\Booking\Actions;

use App\Domain\Booking\Aggregates\BookingAggregateRoot;
use App\Enums\BookingStatus;
use Exception;

class UpdateBookingAction
{
    /**
     * Update a booking.
     *
     * @param  string  $bookingUuid  The UUID of the booking to update
     * @param  BookingStatus|null  $status  The new status of the booking (optional)
     * @param  string|null  $notes  The new notes for the booking (optional)
     *
     * @throws Exception
     */
    public function execute(
        string $bookingUuid,
        ?BookingStatus $status = null,
        ?string $notes = null
    ): void {
        // Only proceed if at least one field is being updated
        if ($status === null && $notes === null) {
            return;
        }

        // Update the booking through the aggregate
        BookingAggregateRoot::retrieve($bookingUuid)
            ->updateBooking(
                status: $status,
                notes: $notes,
            )
            ->persist();
    }
}
