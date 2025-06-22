<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
    ];

    public static function booted()
    {
        static::creating(function (Tenant $tenant) {
            // Ensure domain is lowercase
            $tenant->domain = strtolower($tenant->domain);
        });
    }

    public function getDomainAttribute(): string
    {
        return $this->attributes['domain'];
    }

    public function getFullDomainAttribute(): string
    {
        return "{$this->domain}." . config('app.domain');
    }
}
