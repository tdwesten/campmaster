<?php

namespace App\Models;

use App\Enums\CalculationType;
use App\Models\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Money\Money;

/**
 * BookingItemType
 *
 * Represents a configurable type of additional booking item (e.g. Firewood, Extra guest, Pet).
 * These types define how a booking item is calculated and taxed when attached to a Booking.
 *
 * Attributes
 * - id: string (uuid)
 * - tenant_id: string (uuid)
 * - tax_class_id: string|null (uuid) — optional link to a TaxClass
 * - name: string
 * - description: string|null
 * - calculation_type: CalculationType (enum)
 * - amount_minor: int — price per unit in minor units (e.g. cents)
 * - active: bool
 * - created_at: \Illuminate\Support\Carbon|null
 * - updated_at: \Illuminate\Support\Carbon|null
 * - deleted_at: \Illuminate\Support\Carbon|null
 *
 * Casts
 * - active => bool
 * - calculation_type => CalculationType enum
 * - amount_minor => int
 *
 * Relationships
 * - taxClass(): BelongsTo<TaxClass, BookingItemType>
 *
 * @property string $id
 * @property string $tenant_id
 * @property string|null $tax_class_id
 * @property string $name
 * @property string|null $description
 * @property CalculationType $calculation_type
 * @property int|null $amount_minor
 * @property bool $active
 * @property-read \Illuminate\Support\Carbon|null $created_at
 * @property-read \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Support\Carbon|null $deleted_at
 * @property-read TaxClass|null $taxClass
 */
class BookingItemType extends Model
{
    use HasFactory;
    use HasTenant;
    use HasTimestamps;
    use HasUuids;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'tenant_id',
        'tax_class_id',
        'name',
        'description',
        'calculation_type',
        'amount_minor',
        'active',
    ];

    /**
     * Get the configured price as a Money object using the configured currency (EUR by default).
     */
    public function getAmountAsMoney(): Money
    {
        return Money::EUR($this->amount_minor ?? 0);
    }

    /**
     * The tax class applied to this booking item type.
     *
     * @return BelongsTo<TaxClass, BookingItemType>
     */
    public function taxClass(): BelongsTo
    {
        return $this->belongsTo(TaxClass::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'calculation_type' => CalculationType::class,
            'amount_minor' => 'integer',
        ];
    }
}
