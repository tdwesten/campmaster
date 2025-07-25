<?php

namespace App\Domain\Booking\Events;

use App\Enums\BookingStatus;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class BookingCreated extends ShouldBeStored
{
    public function __construct(
        public string $bookingUuid,
        public string $tenantUuid,
        public string $guestUuid,
        public string $status,
        public string $startDate,
        public string $endDate,
        public ?string $notes = null,
    ) {}

    /**
     * Get the status as a BookingStatus enum.
     */
    public function getStatusEnum(): BookingStatus
    {
        return BookingStatus::from($this->status);
    }
}
