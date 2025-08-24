<?php

namespace App\Domain\BookingItemTypes\Actions;

use App\Models\BookingItemType;

class CreateBookingItemTypeAction
{
    public function execute(array $data): BookingItemType
    {
        return BookingItemType::create($data);
    }
}
