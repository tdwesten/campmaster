<?php

namespace App\Domain\Booking\Projectors;

use App\Domain\Booking\Events\BookingCancelled;
use App\Domain\Booking\Events\BookingCreated;
use App\Domain\Booking\Events\BookingDatesChanged;
use App\Domain\Booking\Events\BookingUpdated;
use App\Domain\Booking\Events\PaymentReceived;
use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class BookingProjector extends Projector
{
    public function onBookingCreated(BookingCreated $event): void
    {
        Booking::create([
            'id' => $event->bookingUuid,
            'tenant_id' => $event->tenantUuid,
            'guest_id' => $event->guestUuid,
            'status' => $event->status,
            'start_date' => $event->startDate,
            'end_date' => $event->endDate,
            'notes' => $event->notes,
        ]);
    }

    public function onBookingUpdated(BookingUpdated $event): void
    {
        $booking = Booking::findOrFail($event->bookingUuid);

        $attributes = [];

        if ($event->status !== null) {
            $attributes['status'] = $event->status;
        }

        if ($event->notes !== null) {
            $attributes['notes'] = $event->notes;
        }

        if (! empty($attributes)) {
            $booking->update($attributes);
        }
    }

    public function onBookingCancelled(BookingCancelled $event): void
    {
        $booking = Booking::findOrFail($event->bookingUuid);
        $booking->update([
            'status' => BookingStatus::Cancelled,
            'notes' => $booking->notes
                ? $booking->notes."\n\nCancellation reason: ".($event->reason ?? 'Not provided')
                : 'Cancellation reason: '.($event->reason ?? 'Not provided'),
        ]);
    }

    public function onBookingDatesChanged(BookingDatesChanged $event): void
    {
        $booking = Booking::findOrFail($event->bookingUuid);
        $booking->update([
            'start_date' => $event->startDate,
            'end_date' => $event->endDate,
        ]);
    }

    public function onPaymentReceived(PaymentReceived $event): void
    {
        $booking = Booking::findOrFail($event->bookingUuid);

        // If the booking is pending, update it to confirmed
        if ($booking->status === BookingStatus::Pending) {
            $booking->update([
                'status' => BookingStatus::Confirmed,
            ]);
        }

        // Here we would also create a payment record in a payments table
        // This is a simplified example, in a real application you would have a Payment model
        DB::table('payments')->insert([
            'id' => Str::uuid()->toString(),
            'booking_id' => $event->bookingUuid,
            'amount' => number_format($event->amount, 2, '.', ''),
            'payment_method' => $event->paymentMethod,
            'reference' => $event->reference,
            'notes' => $event->notes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
