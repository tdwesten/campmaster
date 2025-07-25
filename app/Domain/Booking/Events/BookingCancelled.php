<?php

namespace App\Domain\Booking\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class BookingCancelled extends ShouldBeStored
{
    public function __construct(
        public string $bookingUuid,
        public ?string $reason = null,
    ) {}
}
