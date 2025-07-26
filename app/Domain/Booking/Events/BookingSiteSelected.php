<?php

namespace App\Domain\Booking\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class BookingSiteSelected extends ShouldBeStored
{
    public function __construct(
        public string $bookingUuid,
        public ?string $siteUuid,
    ) {}
}
