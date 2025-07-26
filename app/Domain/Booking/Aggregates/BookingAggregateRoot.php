<?php

namespace App\Domain\Booking\Aggregates;

use App\Domain\Booking\Events\BookingCancelled;
use App\Domain\Booking\Events\BookingCreated;
use App\Domain\Booking\Events\BookingDatesChanged;
use App\Domain\Booking\Events\BookingSiteSelected;
use App\Domain\Booking\Events\BookingUpdated;
use App\Domain\Booking\Events\PaymentReceived;
use App\Enums\BookingStatus;
use Exception;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class BookingAggregateRoot extends AggregateRoot
{
    private ?BookingStatus $status = null;

    private ?string $startDate = null;

    private ?string $endDate = null;

    private float $totalPaid = 0;

    private ?string $siteUuid = null;

    public function createBooking(
        string $tenantUuid,
        string $guestUuid,
        string $startDate,
        string $endDate,
        ?string $notes = null,
    ): self {
        $this->recordThat(new BookingCreated(
            bookingUuid: $this->uuid(),
            tenantUuid: $tenantUuid,
            guestUuid: $guestUuid,
            status: BookingStatus::Pending->value,
            startDate: $startDate,
            endDate: $endDate,
            notes: $notes,
        ));

        return $this;
    }

    public function updateBooking(?BookingStatus $status = null, ?string $notes = null): self
    {
        if ($this->status === BookingStatus::Cancelled) {
            throw new Exception('Cannot update a cancelled booking');
        }

        $this->recordThat(new BookingUpdated(
            bookingUuid: $this->uuid(),
            status: $status?->value,
            notes: $notes,
        ));

        return $this;
    }

    public function cancelBooking(?string $reason = null): self
    {
        if ($this->status === BookingStatus::Cancelled) {
            throw new Exception('Booking is already cancelled');
        }

        $this->recordThat(new BookingCancelled(
            bookingUuid: $this->uuid(),
            reason: $reason,
        ));

        return $this;
    }

    public function changeDates(string $startDate, string $endDate): self
    {
        if ($this->status === BookingStatus::Cancelled) {
            throw new Exception('Cannot change dates of a cancelled booking');
        }

        if ($this->status === BookingStatus::Completed) {
            throw new Exception('Cannot change dates of a completed booking');
        }

        $this->recordThat(new BookingDatesChanged(
            bookingUuid: $this->uuid(),
            startDate: $startDate,
            endDate: $endDate,
        ));

        return $this;
    }

    public function receivePayment(
        float $amount,
        string $paymentMethod,
        ?string $reference = null,
        ?string $notes = null
    ): self {
        if ($this->status === BookingStatus::Cancelled) {
            throw new Exception('Cannot receive payment for a cancelled booking');
        }

        $this->recordThat(new PaymentReceived(
            bookingUuid: $this->uuid(),
            amount: $amount,
            paymentMethod: $paymentMethod,
            reference: $reference,
            notes: $notes,
        ));

        return $this;
    }

    public function selectSite(?string $siteUuid): self
    {
        if ($this->status === BookingStatus::Cancelled) {
            throw new Exception('Cannot select site for a cancelled booking');
        }

        if ($this->status === BookingStatus::Completed) {
            throw new Exception('Cannot select site for a completed booking');
        }

        $this->recordThat(new BookingSiteSelected(
            bookingUuid: $this->uuid(),
            siteUuid: $siteUuid,
        ));

        return $this;
    }

    protected function applyBookingCreated(BookingCreated $event): void
    {
        $this->status = BookingStatus::from($event->status);
        $this->startDate = $event->startDate;
        $this->endDate = $event->endDate;
    }

    protected function applyBookingUpdated(BookingUpdated $event): void
    {
        if ($event->status !== null) {
            $this->status = BookingStatus::from($event->status);
        }
    }

    protected function applyBookingCancelled(BookingCancelled $event): void
    {
        $this->status = BookingStatus::Cancelled;
    }

    protected function applyBookingDatesChanged(BookingDatesChanged $event): void
    {
        $this->startDate = $event->startDate;
        $this->endDate = $event->endDate;
    }

    protected function applyPaymentReceived(PaymentReceived $event): void
    {
        $this->totalPaid += $event->amount;

        // If this is the first payment and the booking is pending, update status to confirmed
        if ($this->status === BookingStatus::Pending) {
            $this->status = BookingStatus::Confirmed;
        }
    }

    protected function applyBookingSiteSelected(BookingSiteSelected $event): void
    {
        $this->siteUuid = $event->siteUuid;
    }
}
