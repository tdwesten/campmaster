<?php

use App\Domain\Booking\Actions\CancelBookingAction;
use App\Domain\Booking\Actions\ChangeBookingDatesAction;
use App\Domain\Booking\Actions\CreateBookingAction;
use App\Domain\Booking\Actions\ReceiveBookingPaymentAction;
use App\Domain\Booking\Actions\UpdateBookingAction;
use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();
    $this->tenant->makeCurrent();
    $this->guest = Guest::factory()->create();
});

test('booking creation flow', function () {
    // Create a booking using the CreateBookingAction
    $bookingUuid = (new CreateBookingAction)->execute(
        guestUuid: $this->guest->id,
        startDate: '2025-08-01',
        endDate: '2025-08-07',
        notes: 'Test booking creation flow'
    );

    // Verify the booking was created correctly
    $booking = Booking::find($bookingUuid);

    expect($booking)->not->toBeNull()
        ->and($booking->tenant_id)->toBe($this->tenant->id)
        ->and($booking->guest_id)->toBe($this->guest->id)
        ->and($booking->status)->toBe(BookingStatus::Pending)
        ->and($booking->start_date->format('Y-m-d'))->toBe('2025-08-01')
        ->and($booking->end_date->format('Y-m-d'))->toBe('2025-08-07')
        ->and($booking->notes)->toBe('Test booking creation flow');
});

test('booking update flow', function () {
    // Create a booking
    $bookingUuid = (new CreateBookingAction)->execute(
        guestUuid: $this->guest->id,
        startDate: '2025-08-01',
        endDate: '2025-08-07'
    );

    // Update the booking status and notes
    (new UpdateBookingAction)->execute(
        bookingUuid: $bookingUuid,
        status: BookingStatus::Confirmed,
        notes: 'Updated notes'
    );

    // Verify the booking was updated correctly
    $booking = Booking::find($bookingUuid);
    expect($booking->status)->toBe(BookingStatus::Confirmed)
        ->and($booking->notes)->toBe('Updated notes');
});

test('booking cancellation flow', function () {
    // Create a booking
    $bookingUuid = (new CreateBookingAction)->execute(
        guestUuid: $this->guest->id,
        startDate: '2025-08-01',
        endDate: '2025-08-07'
    );

    // Cancel the booking
    (new CancelBookingAction)->execute(
        bookingUuid: $bookingUuid,
        reason: 'Guest requested cancellation'
    );

    // Verify the booking was cancelled correctly
    $booking = Booking::find($bookingUuid);
    expect($booking->status)->toBe(BookingStatus::Cancelled)
        ->and($booking->notes)->toContain('Guest requested cancellation');
});

test('booking dates change flow', function () {
    // Create a booking
    $bookingUuid = (new CreateBookingAction)->execute(
        guestUuid: $this->guest->id,
        startDate: '2025-08-01',
        endDate: '2025-08-07'
    );

    // Change the booking dates
    (new ChangeBookingDatesAction)->execute(
        bookingUuid: $bookingUuid,
        startDate: '2025-08-15',
        endDate: '2025-08-22'
    );

    // Verify the booking dates were changed correctly
    $booking = Booking::find($bookingUuid);
    expect($booking->start_date->format('Y-m-d'))->toBe('2025-08-15')
        ->and($booking->end_date->format('Y-m-d'))->toBe('2025-08-22');
});

test('booking payment flow', function () {
    // Create a booking
    $bookingUuid = (new CreateBookingAction)->execute(
        guestUuid: $this->guest->id,
        startDate: '2025-08-01',
        endDate: '2025-08-07'
    );

    // Record a payment for the booking
    (new ReceiveBookingPaymentAction)->execute(
        bookingUuid: $bookingUuid,
        amount: 100.50,
        paymentMethod: 'credit_card',
        reference: 'PAYMENT123',
        notes: 'Deposit payment'
    );

    // Verify the booking status was updated to Confirmed
    $booking = Booking::find($bookingUuid);
    expect($booking->status)->toBe(BookingStatus::Confirmed);

    // Verify the payment was recorded in the payments table
    $payment = DB::table('payments')->where('booking_id', $bookingUuid)->first();
    expect($payment)->not->toBeNull()
        ->and($payment->amount)->toBe(100.50)
        ->and($payment->payment_method)->toBe('credit_card')
        ->and($payment->reference)->toBe('PAYMENT123')
        ->and($payment->notes)->toBe('Deposit payment');
});

test('cannot update cancelled booking', function () {
    // Create a booking
    $bookingUuid = (new CreateBookingAction)->execute(
        guestUuid: $this->guest->id,
        startDate: '2025-08-01',
        endDate: '2025-08-07'
    );

    // Cancel the booking
    (new CancelBookingAction)->execute(
        bookingUuid: $bookingUuid
    );

    // Attempt to update the cancelled booking
    expect(fn () => (new UpdateBookingAction)->execute(
        bookingUuid: $bookingUuid,
        status: BookingStatus::Confirmed
    ))->toThrow(Exception::class, 'Cannot update a cancelled booking');
});

test('cannot change dates of cancelled booking', function () {
    // Create a booking
    $bookingUuid = (new CreateBookingAction)->execute(
        guestUuid: $this->guest->id,
        startDate: '2025-08-01',
        endDate: '2025-08-07'
    );

    // Cancel the booking
    (new CancelBookingAction)->execute(
        bookingUuid: $bookingUuid
    );

    // Attempt to change dates of the cancelled booking
    expect(fn () => (new ChangeBookingDatesAction)->execute(
        bookingUuid: $bookingUuid,
        startDate: '2025-08-15',
        endDate: '2025-08-22'
    ))->toThrow(Exception::class, 'Cannot change dates of a cancelled booking');
});

test('cannot receive payment for cancelled booking', function () {
    // Create a booking
    $bookingUuid = (new CreateBookingAction)->execute(
        guestUuid: $this->guest->id,
        startDate: '2025-08-01',
        endDate: '2025-08-07'
    );

    // Cancel the booking
    (new CancelBookingAction)->execute(
        bookingUuid: $bookingUuid
    );

    // Attempt to receive payment for the cancelled booking
    expect(fn () => (new ReceiveBookingPaymentAction)->execute(
        bookingUuid: $bookingUuid,
        amount: 100.50,
        paymentMethod: 'credit_card'
    ))->toThrow(Exception::class, 'Cannot receive payment for a cancelled booking');
});
