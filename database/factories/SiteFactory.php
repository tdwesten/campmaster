<?php

namespace Database\Factories;

use App\Models\Site;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Site>
 */
class SiteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Site>
     */
    protected $model = Site::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tenant = Tenant::current() ?? Tenant::factory()->create();
        $tenant->makeCurrent();

        return [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenant->id,
            'name' => 'Site '.fake()->unique()->numberBetween(1, 9999),
            'description' => fake()->optional()->sentence(12),
        ];
    }
}
