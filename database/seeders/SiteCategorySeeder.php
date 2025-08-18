<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\SiteCategory;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SiteCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        // For each tenant, ensure categories exist, then assign to sites
        Tenant::all()->each(function (Tenant $tenant) use ($siteTypes): void {
            // Create/update a category per type for this tenant
            $categories = collect($siteTypes)->map(function (string $description, string $name) use ($tenant) {
                return SiteCategory::updateOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'name' => $name,
                    ],
                    [
                        'id' => Str::uuid()->toString(),
                        'description' => $description,
                    ]
                );
            })->values();

            // Assign a random category to each site of this tenant that has none
            Site::query()
                ->where('tenant_id', $tenant->id)
                ->whereNull('site_category_id')
                ->get()
                ->each(function (Site $site) use ($categories): void {
                    $category = $categories->random();
                    $site->update(['site_category_id' => $category->id]);
                });
        });
    }
}
