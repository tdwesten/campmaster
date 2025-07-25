<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+6 months');
        $endDate = fake()->dateTimeBetween(
            $startDate->format('Y-m-d'),
            (clone $startDate)->modify('+14 days')->format('Y-m-d')
        );

        return [
            'tenant_id' => Tenant::current() ?? Tenant::factory(),
            'guest_id' => Guest::factory(),
            'status' => fake()->randomElement(BookingStatus::cases()),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'notes' => fake()->boolean(70) ? fake()->paragraph() : null,
        ];
    }
}
