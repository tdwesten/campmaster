<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    /**
     * Seed the guests table with demo guests for each tenant.
     */
    public function run(): void
    {
        // For each tenant, set it as current and create guests
        Tenant::all()->each(function (Tenant $tenant): void {
            $tenant->makeCurrent();

            // Create 42 guests per tenant by default
            Guest::factory()->count(42)->create([
                'tenant_id' => $tenant->id,
            ]);
        });
    }
}
