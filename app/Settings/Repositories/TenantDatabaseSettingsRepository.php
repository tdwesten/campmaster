<?php

namespace App\Settings\Repositories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository;

class TenantDatabaseSettingsRepository extends DatabaseSettingsRepository
{
    /**
     * Get the current tenant ID or null if no tenant is current.
     */
    protected function getCurrentTenantId(): ?string
    {
        $tenant = Tenant::current();

        return $tenant?->id;
    }

    /**
     * Get the builder with tenant filtering applied.
     */
    public function getBuilder(): Builder
    {
        $builder = parent::getBuilder();

        $tenantId = $this->getCurrentTenantId();

        // If we have a current tenant, filter by it or allow null tenant_id (global settings)
        if ($tenantId !== null) {
            $builder->where(function (Builder $query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)
                    ->orWhereNull('tenant_id');
            });
        }

        return $builder;
    }

    /**
     * Create a new settings property.
     *
     * @param  mixed  $payload
     */
    public function createProperty(string $group, string $name, $payload): void
    {
        $this->getBuilder()->create([
            'tenant_id' => $this->getCurrentTenantId(),
            'group' => $group,
            'name' => $name,
            'payload' => $this->encode($payload),
            'locked' => false,
        ]);
    }

    /**
     * Update properties payload with tenant awareness.
     */
    public function updatePropertiesPayload(string $group, array $properties): void
    {
        $tenantId = $this->getCurrentTenantId();

        $propertiesInBatch = collect($properties)->map(function ($payload, $name) use ($group, $tenantId) {
            return [
                'group' => $group,
                'name' => $name,
                'tenant_id' => $tenantId,
                'payload' => $this->encode($payload),
            ];
        })->values()->toArray();

        // For tenant-specific settings, we need to include tenant_id in the unique constraint
        if ($tenantId !== null) {
            $this->getBuilder()
                ->where('group', $group)
                ->upsert($propertiesInBatch, ['group', 'name', 'tenant_id'], ['payload']);
        } else {
            // For global settings (no tenant), use the default behavior
            parent::updatePropertiesPayload($group, $properties);
        }
    }
}
