<?php

namespace Database\Factories;

use App\Models\SiteCategory;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SiteCategory>
 */
class SiteCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<SiteCategory>
     */
    protected $model = SiteCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Standard',
            'Premium',
            'Deluxe',
            'Waterfront',
            'Wooded',
            'Group',
            'RV',
            'Cabin',
            'Tent Only',
            'ADA Accessible',
        ]);

        // Ensure a tenant is current or explicitly set
        $tenant = Tenant::current() ?? Tenant::factory()->create();
        $tenant->makeCurrent();

        return [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenant->id,
            'name' => $name,
            'description' => fake()->sentence(10),
        ];
    }
}
