<?php

namespace App\Policies;

use App\Models\BookingItemType;
use App\Models\User;

class BookingItemTypePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // TODO: integrate roles (Admin/Manager)
    }

    public function view(User $user, BookingItemType $bookingItemType): bool
    {
        return true; // TODO: integrate roles
    }

    public function create(User $user): bool
    {
        // TODO: restrict to Admin when roles exist
        return true;
    }

    public function update(User $user, BookingItemType $bookingItemType): bool
    {
        // TODO: restrict to Admin when roles exist
        return true;
    }

    public function delete(User $user, BookingItemType $bookingItemType): bool
    {
        // TODO: restrict to Admin when roles exist
        return true;
    }
}
