<?php

namespace App\Enums;

enum IntervalType: string
{
    case PerNight = 'per_night';
    case PerStay = 'per_stay';
    case PerUnit = 'per_unit';
}
