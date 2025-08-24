<?php

declare(strict_types=1);

use App\Models\TaxClass;
use App\Models\Tenant;

it('factory creates a valid TaxClass (feature env)', function () {
    $tenant = Tenant::factory()->create();
    $tenant->makeCurrent();

    $tax = TaxClass::factory()->create([
        'rate_bps' => 2100,
    ]);

    expect($tax)->toBeInstanceOf(TaxClass::class)
        ->and($tax->tenant_id)->toBe($tenant->id)
        ->and($tax->rate_bps)->toBe(2100);
});
