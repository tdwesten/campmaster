<?php

namespace App\Enums;

enum CalculationType: string
{
    case Percentage = 'percentage';
    case Fixed = 'fixed';
    case Unit = 'unit';
    case Night = 'night';
}
