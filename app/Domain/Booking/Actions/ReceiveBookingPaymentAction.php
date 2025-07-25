<?php

namespace App\Domain\Booking\Actions;

use App\Domain\Booking\Aggregates\BookingAggregateRoot;

class ReceiveBookingPaymentAction
{
    /**
     * Record a payment for a booking.
     *
     * @param  string  $bookingUuid  The UUID of the booking
     * @param  float  $amount  The payment amount
     * @param  string  $paymentMethod  The method of payment (e.g., credit card, cash)
     * @param  string|null  $reference  An optional payment reference
     * @param  string|null  $notes  Optional notes about the payment
     */
    public function execute(
        string $bookingUuid,
        float $amount,
        string $paymentMethod,
        ?string $reference = null,
        ?string $notes = null
    ): void {
        // Record the payment through the aggregate
        BookingAggregateRoot::retrieve($bookingUuid)
            ->receivePayment(
                amount: $amount,
                paymentMethod: $paymentMethod,
                reference: $reference,
                notes: $notes,
            )
            ->persist();
    }
}
