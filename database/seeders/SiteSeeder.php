<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all tenants
        $tenants = Tenant::all();

        // Site types and their descriptions
        $siteTypes = [
            'Standard' => 'A basic campsite with space for a tent or small camper.',
            'Premium' => 'A spacious site with electricity and water hookups.',
            'Deluxe' => 'Our best site with full hookups, a fire pit, and picnic table.',
            'Waterfront' => 'Beautiful site located right on the water with amazing views.',
            'Wooded' => 'Secluded site surrounded by trees for extra privacy.',
            'Group' => 'Large site perfect for family gatherings or group camping.',
            'RV' => 'Dedicated site for RVs with full hookups and easy access.',
            'Cabin' => 'Rustic cabin site with basic amenities for a comfortable stay.',
            'Tent Only' => 'Site specifically designed for tent camping with level ground.',
            'ADA Accessible' => 'Fully accessible site with paved paths and proximity to facilities.',
        ];

        foreach ($tenants as $tenant) {
            // Create 10 sites for each tenant
            for ($i = 1; $i <= 10; $i++) {
                // Randomly select a site type
                $siteType = array_rand($siteTypes);
                $description = $siteTypes[$siteType];

                // Create the site
                Site::create([
                    'id' => Str::uuid()->toString(),
                    'tenant_id' => $tenant->id,
                    'name' => "Site {$i} - {$siteType}",
                    'description' => $description,
                ]);
            }
        }
    }
}
