<?php

namespace Database\Factories;

use App\Models\TaxClass;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TaxClass>
 */
class TaxClassFactory extends Factory
{
    protected $model = TaxClass::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'tenant_id' => Tenant::current()?->id ?? Tenant::factory(),
            'name' => $this->faker->unique()->words(2, true),
            'rate_bps' => $this->faker->randomElement([0, 900, 2100]),
        ];
    }
}
