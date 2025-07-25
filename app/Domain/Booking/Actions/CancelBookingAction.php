<?php

namespace App\Domain\Booking\Actions;

use App\Domain\Booking\Aggregates\BookingAggregateRoot;

class CancelBookingAction
{
    /**
     * Cancel a booking.
     *
     * @param  string  $bookingUuid  The UUID of the booking to cancel
     * @param  string|null  $reason  The reason for cancellation (optional)
     */
    public function execute(
        string $bookingUuid,
        ?string $reason = null
    ): void {
        // Cancel the booking through the aggregate
        BookingAggregateRoot::retrieve($bookingUuid)
            ->cancelBooking(
                reason: $reason,
            )
            ->persist();
    }
}
