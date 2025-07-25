<?php

namespace App\Domain\Booking\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PaymentReceived extends ShouldBeStored
{
    public function __construct(
        public string $bookingUuid,
        public float $amount,
        public string $paymentMethod,
        public ?string $reference = null,
        public ?string $notes = null,
    ) {}
}
