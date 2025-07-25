<?php

namespace App\Domain\Booking\Events;

use App\Enums\BookingStatus;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class BookingUpdated extends ShouldBeStored
{
    public function __construct(
        public string $bookingUuid,
        public ?string $status = null,
        public ?string $notes = null,
    ) {}

    /**
     * Get the status as a BookingStatus enum.
     */
    public function getStatusEnum(): ?BookingStatus
    {
        return $this->status !== null ? BookingStatus::from($this->status) : null;
    }
}
