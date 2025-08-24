<?php

namespace App\Models;

use App\Enums\CalculationType;
use App\Enums\IntervalType;
use App\Models\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * TaxClass
 *
 * Defines how tax should be applied to booking items or totals for a tenant.
 * Supports either a fixed amount (in minor units) or a percentage rate expressed in basis points (bps).
 *
 * Attributes
 * - id: string (uuid)
 * - tenant_id: string (uuid)
 * - name: string
 * - description: string|null
 * - interval_type: IntervalType (enum) — e.g. per night or per period
 * - calculation_type: CalculationType (enum) — e.g. percentage or fixed amount
 * - active: bool
 * - fixed_amount_minor: int|null — fixed tax in minor units (e.g. cents)
 * - rate_bps: int|null — percentage rate in basis points (1% = 100 bps)
 * - created_at: \Illuminate\Support\Carbon|null
 * - updated_at: \Illuminate\Support\Carbon|null
 *
 * Casts
 * - active => bool
 * - interval_type => IntervalType enum
 * - calculation_type => CalculationType enum
 * - fixed_amount_minor => int
 * - rate_bps => int
 *
 * @property string $id
 * @property string $tenant_id
 * @property string $name
 * @property string|null $description
 * @property IntervalType $interval_type
 * @property CalculationType $calculation_type
 * @property bool $active
 * @property int|null $fixed_amount_minor
 * @property int|null $rate_bps
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 */
class TaxClass extends Model
{
    use HasFactory;
    use HasTenant;
    use HasUuids;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'tenant_id',
        'name',
        'description',
        'interval_type',
        'calculation_type',
        'active',
        'fixed_amount_minor',
        'rate_bps',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'interval_type' => IntervalType::class,
            'calculation_type' => CalculationType::class,
            'percentage' => 'integer',
            'fixed_amount_minor' => 'integer',
            'rate_bps' => 'integer',
        ];
    }
}
