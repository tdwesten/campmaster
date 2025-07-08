<?php

namespace App\SettingsRepositories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository;

class TenantDatabaseSettingsRepository extends DatabaseSettingsRepository
{
    protected ?string $tenantId = null;

    public function setTenantId(?string $tenantId): self
    {
        $this->tenantId = $tenantId;

        return $this;
    }

    public function getTenantId(): ?string
    {
        if ($this->tenantId) {
            return $this->tenantId;
        }

        $tenant = Tenant::current();

        return $tenant?->id;
    }

    public function getBuilder(): Builder
    {
        $builder = parent::getBuilder();

        $tenantId = $this->getTenantId();

        if ($tenantId) {
            $builder->where('tenant_id', $tenantId);
        }

        return $builder;
    }

    public function createProperty(string $group, string $name, $payload): void
    {
        $tenantId = $this->getTenantId();

        $data = [
            'group' => $group,
            'name' => $name,
            'payload' => $this->encode($payload),
            'locked' => false,
        ];

        if ($tenantId) {
            $data['tenant_id'] = $tenantId;
        }

        $this->getBuilder()->withoutGlobalScopes()->create($data);
    }

    public function updatePropertiesPayload(string $group, array $properties): void
    {
        $tenantId = $this->getTenantId();

        // For each property, check if it exists and update it, or create it if it doesn't exist
        foreach ($properties as $name => $payload) {
            $query = $this->getBuilder()
                ->where('group', $group)
                ->where('name', $name);

            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }

            $exists = $query->exists();

            $data = [
                'payload' => $this->encode($payload),
                'updated_at' => now(),
            ];

            if ($exists) {
                // Update existing property
                $query->update($data);
            } else {
                // Create new property
                $data['group'] = $group;
                $data['name'] = $name;
                $data['created_at'] = now();

                if ($tenantId) {
                    $data['tenant_id'] = $tenantId;
                }

                $this->getBuilder()->insert($data);
            }
        }
    }

    /**
     * @param  mixed  $value
     * @return mixed
     */
    protected function encode($value)
    {
        $encoder = config('settings.encoder') ?? fn ($value) => json_encode($value);

        return $encoder($value);
    }

    public function getPropertiesInGroup(string $group): array
    {
        $tenantId = $this->getTenantId();

        $query = $this->getBuilder()
            ->where('group', $group);

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        $properties = $query->get(['name', 'payload'])
            ->mapWithKeys(function (object $object) {
                return [$object->name => $this->decode($object->payload, true)];
            })
            ->toArray();

        return $properties;
    }

    /**
     * @return mixed
     */
    protected function decode(string $payload, bool $associative = false)
    {
        $decoder = config('settings.decoder') ?? fn ($payload, $associative) => json_decode($payload, $associative);

        return $decoder($payload, $associative);
    }
}
