<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Models\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;
    use HasTenant;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'tenant_id',
        'guest_id',
        'site_id',
        'status',
        'start_date',
        'end_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => BookingStatus::class,
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the guest that owns the booking.
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * Get the site that the booking is for.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
