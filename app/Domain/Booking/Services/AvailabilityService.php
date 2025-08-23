<?php

namespace App\Domain\Booking\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Site;
use App\Settings\BookingSettings;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AvailabilityService
{
    public function __construct(private BookingSettings $settings) {}

    /**
     * Get available Site models for the given date range, optionally limited to specific sites.
     *
     * @param  array<string>|Collection<string>  $siteIds
     * @return Collection<int,Site>
     */
    public function availableSites(Carbon $startDate, Carbon $endDate, array|Collection $siteIds = [], ?int $offsetDays = null): Collection
    {
        $ids = $siteIds instanceof Collection ? $siteIds->all() : $siteIds;
        $siteQuery = Site::query();
        if (! empty($ids)) {
            $siteQuery->whereIn('id', $ids);
        }
        $sites = $siteQuery->get();

        return $sites->filter(function (Site $site) use ($startDate, $endDate, $offsetDays) {
            return $this->isSiteAvailable($site->id, $startDate, $endDate, $offsetDays);
        })->values();
    }

    /**
     * Determine if a site is available between start and end dates (end is checkout, exclusive).
     */
    public function isSiteAvailable(string $siteId, Carbon $startDate, Carbon $endDate, ?int $offsetDays = null): bool
    {
        $offset = $offsetDays ?? $this->settings->offset_between_bookings_days;

        $conflicts = Booking::query()
            ->where('site_id', $siteId)
            ->where('status', '!=', BookingStatus::Cancelled->value)
            // conflict if booking.end_date > (startDate - offset)
            ->whereDate('end_date', '>', $startDate->copy()->subDays($offset)->toDateString())
            // and booking.start_date < (endDate + offset)
            ->whereDate('start_date', '<', $endDate->copy()->addDays($offset)->toDateString())
            ->exists();

        return ! $conflicts;
    }
}
