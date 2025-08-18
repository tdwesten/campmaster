<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 generic sites per tenant; categories are handled by SiteCategorySeeder
        Tenant::all()->each(function (Tenant $tenant): void {
            for ($i = 1; $i <= 10; $i++) {
                Site::factory()->create([
                    'tenant_id' => $tenant->id,
                    'name' => "Site {$i}",
                ]);
            }
        });
    }
}
