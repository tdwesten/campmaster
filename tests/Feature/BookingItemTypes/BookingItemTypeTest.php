<?php

namespace Tests\Feature\BookingItemTypes;

use App\Enums\CalculationType;
use App\Enums\IntervalType;
use App\Models\BookingItemType;
use App\Models\TaxClass;
use App\Models\Tenant;
use DateTimeInterface;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('app.domain', 'campmaster.nl');
    $this->runLandlordMigrations();

    $tenant = Tenant::factory()->create(['name' => 'Camping De Nachtegaal']);
    $tenant->makeCurrent();
});

test('can create a "child under 5 year" type', function () {

    $taxClass = TaxClass::create([
        'name' => 'Toeristen Belasting',
        'rate_bps' => 150, // 1.5%
        'description' => 'Toeristen belasting per persoon per nacht',
        'active' => true,
        'interval_type' => IntervalType::PerNight,
        'calculation_type' => CalculationType::Percentage,
    ]);

    $bookingItemType = BookingItemType::create([
        'name' => 'Child Under 5 Year',
        'description' => 'Child under 5 year per person per night',
        'amount_minor' => 0,
        'calculation_type' => CalculationType::Night,
        'tax_class_id' => $taxClass->id,
        'active' => true,
    ]);

    expect($bookingItemType->id)->not->toBeNull()
        ->and($bookingItemType->name)->toBe('Child Under 5 Year')
        ->and($bookingItemType->description)->toBe('Child under 5 year per person per night')
        ->and($bookingItemType->getAmountAsMoney()->getAmount())->toBe('0')
        ->and($bookingItemType->calculation_type)->toBe(CalculationType::Night)
        ->and($bookingItemType->active)->toBeTrue()
        ->and($bookingItemType->tenant_id)->toBe(Tenant::current()->id)
        ->and($bookingItemType->created_at)->toBeInstanceOf(DateTimeInterface::class)
        ->and($bookingItemType->updated_at)->toBeInstanceOf(DateTimeInterface::class);
});

test('can create a "firewood" type', function () {
    $bookingItemType = BookingItemType::create([
        'name' => 'Firewood',
        'description' => 'Firewood per bundle',
        'amount_minor' => 500,
        'calculation_type' => CalculationType::Unit,
        'active' => true,
    ]);

    expect($bookingItemType->id)->not->toBeNull()
        ->and($bookingItemType->name)->toBe('Firewood')
        ->and($bookingItemType->description)->toBe('Firewood per bundle')
        ->and($bookingItemType->getAmountAsMoney()->getAmount())->toBe('500')
        ->and($bookingItemType->calculation_type)->toBe(CalculationType::Unit)
        ->and($bookingItemType->active)->toBeTrue()
        ->and($bookingItemType->tenant_id)->toBe(Tenant::current()->id)
        ->and($bookingItemType->created_at)->toBeInstanceOf(DateTimeInterface::class)
        ->and($bookingItemType->updated_at)->toBeInstanceOf(DateTimeInterface::class);
});
