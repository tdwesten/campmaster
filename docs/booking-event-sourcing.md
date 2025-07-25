# Booking Domain Event Sourcing

This document provides an overview of the event sourcing implementation for the booking domain in Campmaster.

## Overview

The booking domain has been implemented using event sourcing with the Spatie Event Sourcing package. This approach
provides several benefits:

- Complete audit trail of all changes to bookings
- Ability to reconstruct the state of a booking at any point in time
- Clear separation of business logic (in aggregates) from state representation (in projections)
- Improved testability and maintainability

## Architecture

The booking domain event sourcing implementation consists of the following components:

### Events

Events represent things that have happened in the system. They are immutable and are stored in the event store.

- `BookingCreated`: Fired when a new booking is created
- `BookingUpdated`: Fired when a booking's details are updated
- `BookingCancelled`: Fired when a booking is cancelled
- `BookingDatesChanged`: Fired when a booking's dates are changed
- `PaymentReceived`: Fired when a payment is received for a booking

### Aggregate

The `BookingAggregateRoot` encapsulates all business logic for bookings and ensures that the system remains in a
consistent state. It:

- Validates commands before recording events
- Enforces business rules (e.g., cannot update a cancelled booking)
- Records events that represent state changes
- Applies events to update its internal state

### Projector

The `BookingProjector` listens for booking-related events and updates the read model (the `Booking` model and related
tables) accordingly. This ensures that the read model always reflects the current state of the system.

### Actions

Actions provide a simple interface for interacting with the booking domain. They:

- Accept input from controllers or other parts of the application
- Interact with the aggregate to perform operations
- Return results that can be used by the caller

## Usage Examples

### Creating a Booking

```php
use App\Domain\Booking\Actions\CreateBookingAction;

// Create a new booking
$bookingUuid = (new CreateBookingAction())->execute(
    guestUuid: $guest->id,
    startDate: '2025-08-01',
    endDate: '2025-08-07',
    notes: 'Special requests: late check-in'
);

// The booking UUID can be used to retrieve the booking
$booking = Booking::find($bookingUuid);
```

### Updating a Booking

```php
use App\Domain\Booking\Actions\UpdateBookingAction;
use App\Enums\BookingStatus;

// Update a booking's status and/or notes
(new UpdateBookingAction())->execute(
    bookingUuid: $booking->id,
    status: BookingStatus::Confirmed,
    notes: 'Updated notes'
);
```

### Cancelling a Booking

```php
use App\Domain\Booking\Actions\CancelBookingAction;

// Cancel a booking
(new CancelBookingAction())->execute(
    bookingUuid: $booking->id,
    reason: 'Guest requested cancellation'
);
```

### Changing Booking Dates

```php
use App\Domain\Booking\Actions\ChangeBookingDatesAction;

// Change a booking's dates
(new ChangeBookingDatesAction())->execute(
    bookingUuid: $booking->id,
    startDate: '2025-08-15',
    endDate: '2025-08-22'
);
```

### Recording a Payment

```php
use App\Domain\Booking\Actions\ReceiveBookingPaymentAction;

// Record a payment for a booking
(new ReceiveBookingPaymentAction())->execute(
    bookingUuid: $booking->id,
    amount: 100.50,
    paymentMethod: 'credit_card',
    reference: 'PAYMENT123',
    notes: 'Deposit payment'
);
```

## Business Rules

The booking domain enforces several business rules:

1. A booking starts in the `Pending` status
2. When a payment is received for a `Pending` booking, its status is updated to `Confirmed`
3. A cancelled booking cannot be updated, have its dates changed, or receive payments
4. A completed booking cannot have its dates changed

These rules are enforced by the `BookingAggregateRoot` and are tested in the `BookingEventSourcingTest` file.

## Testing

The booking domain event sourcing implementation is thoroughly tested in:

1. `tests/Feature/BookingTest.php`: Tests the basic functionality of the Booking model and its relationships
2. `tests/Feature/BookingEventSourcingTest.php`: Tests the event sourcing implementation, including all actions and
   business rules

## Extending the Booking Domain

To add new functionality to the booking domain:

1. Define new events in `app/Domain/Booking/Events/`
2. Add methods to the `BookingAggregateRoot` to record and apply these events
3. Update the `BookingProjector` to handle the new events
4. Create new actions in `app/Domain/Booking/Actions/` to provide a simple interface for the new functionality
5. Add tests for the new functionality

## Troubleshooting

### Events Not Being Processed

If events are not being processed by the projector, check:

1. That the projector is registered in the event-sourcing.php config file or via auto-discovery
2. That the event classes are correctly namespaced and extend `ShouldBeStored`
3. That the projector methods follow the naming convention `on{EventName}`

### Aggregate State Not Being Updated

If the aggregate's state is not being updated correctly, check:

1. That the aggregate has `apply{EventName}` methods for all events
2. That these methods correctly update the aggregate's internal state
3. That events are being recorded with the correct data

### Database Inconsistencies

If the database state doesn't match the expected state after events are processed, check:

1. That the projector is correctly handling all events
2. That the projector methods are updating the database correctly
3. That there are no exceptions being thrown during event processing
