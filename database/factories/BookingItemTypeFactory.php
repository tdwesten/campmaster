<?php

namespace Database\Factories;

use App\Enums\IntervalType;
use App\Models\BookingItemType;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BookingItemType>
 */
class BookingItemTypeFactory extends Factory
{
    protected $model = BookingItemType::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'tenant_id' => Tenant::current()?->id ?? Tenant::factory(),
            'name' => $this->faker->unique()->words(2, true),
            'description' => $this->faker->optional()->sentence(10),
            'interval' => $this->faker->randomElement(IntervalType::cases()),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'tax_class_id' => null,
            'active' => $this->faker->boolean(90),
        ];
    }
}
