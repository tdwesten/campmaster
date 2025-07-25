<?php

namespace App\Domain\Booking\Actions;

use App\Domain\Booking\Aggregates\BookingAggregateRoot;
use App\Models\Tenant;
use Illuminate\Support\Str;

class CreateBookingAction
{
    /**
     * Create a new booking.
     *
     * @param  string  $guestUuid  The UUID of the guest
     * @param  string  $startDate  The start date of the booking (Y-m-d format)
     * @param  string  $endDate  The end date of the booking (Y-m-d format)
     * @param  string|null  $notes  Optional notes for the booking
     * @param  string|null  $tenantUuid  The UUID of the tenant (if not provided, the current tenant will be used)
     * @return string The UUID of the created booking
     */
    public function execute(
        string $guestUuid,
        string $startDate,
        string $endDate,
        ?string $notes = null,
        ?string $tenantUuid = null
    ): string {
        // If tenant UUID is not provided, use the current tenant
        if ($tenantUuid === null) {
            $tenantUuid = Tenant::current()->getKey();
        }

        // Generate a UUID for the new booking
        $bookingUuid = (string) Str::uuid();

        // Create and persist the booking through the aggregate
        BookingAggregateRoot::retrieve($bookingUuid)
            ->createBooking(
                tenantUuid: $tenantUuid,
                guestUuid: $guestUuid,
                startDate: $startDate,
                endDate: $endDate,
                notes: $notes,
            )
            ->persist();

        return $bookingUuid;
    }
}
