<?php

namespace App\Models\Traits;

use App\Exceptions\TenantIdNotFillableException;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasTenant
{
    /**
     * Boot the trait.
     *
     * This method is automatically called when the trait is used.
     *
     * @throws TenantIdNotFillableException
     */
    public static function bootHasTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            $builder->tenant();
        });

        static::creating(function ($model) {

            if (! in_array('tenant_id', $model->getFillable())) {
                throw new \App\Exceptions\TenantIdNotFillableException(get_class($model));
            }

            if (Tenant::current() === null) {
                throw new \Exception('No current tenant');
            }

            if ($model->tenant_id === null) {
                $model->tenant_id = Tenant::current()->id;
            }
        });

    }

    /**
     * Scope a query to only include records for the current tenant.
     */
    public function scopeTenant(Builder $builder, ?Tenant $tenant = null): Builder
    {
        $tenant = $tenant ?? Tenant::current();

        if (! $tenant) {
            return $builder;
        }

        // If the model has a direct tenant_id column
        if ($this->hasTenantIdColumn()) {
            return $builder->where($this->qualifyColumn('tenant_id'), $tenant->getKey());
        }

        // If the model has a many-to-many relationship with tenants
        if (method_exists($this, 'tenants') && $this->tenants() instanceof BelongsToMany) {
            return $builder->whereHas('tenants', function (Builder $query) use ($tenant) {
                $query->where('tenants.id', $tenant->getKey());
            });
        }

        // If the model has a relationship with a model that has a tenant_id
        // For example, Guest has bookings that have tenant_id
        if (method_exists($this, 'bookings')) {
            return $builder->whereHas('bookings', function (Builder $query) use ($tenant) {
                $query->where('bookings.tenant_id', $tenant->getKey());
            });
        }

        return $builder;
    }

    /**
     * Determine if the model has a tenant_id column.
     */
    protected function hasTenantIdColumn(): bool
    {
        return in_array('tenant_id', $this->getFillable()) ||
            method_exists($this, 'tenant') && $this->tenant() instanceof BelongsTo;
    }

    /**
     * Get the tenant that owns the model.
     *
     * This method should be implemented in models that have a direct tenant_id column.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
