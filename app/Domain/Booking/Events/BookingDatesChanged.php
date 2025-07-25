<?php

namespace App\Domain\Booking\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class BookingDatesChanged extends ShouldBeStored
{
    public function __construct(
        public string $bookingUuid,
        public string $startDate,
        public string $endDate,
    ) {}
}
