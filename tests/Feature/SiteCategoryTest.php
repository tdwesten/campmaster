<?php

use App\Models\Site;
use App\Models\SiteCategory;
use App\Models\Tenant;
use Database\Seeders\SiteCategorySeeder;

test('site belongs to one site category and category has many sites', function () {
    $tenant = Tenant::factory()->create();
    $tenant->makeCurrent();

    /** @var SiteCategory $category */
    $category = SiteCategory::factory()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Premium',
    ]);

    /** @var Site $site */
    $site = Site::factory()->create([
        'tenant_id' => $tenant->id,
        'site_category_id' => $category->id,
    ]);

    // Reload relations
    $site->unsetRelation('siteCategory');

    expect($site->siteCategory)->not->toBeNull()
        ->and($site->siteCategory->id)->toBe($category->id)
        ->and($site->siteCategory->tenant_id)->toBe($tenant->id)
        ->and($category->sites()->pluck('id')->all())->toContain($site->id);
});

test('seeder creates categories per tenant and assigns every site a category', function () {
    $tenant = Tenant::factory()->create();
    $tenant->makeCurrent();

    // Create 5 sites for the tenant
    Site::factory()->count(5)->create(['tenant_id' => $tenant->id]);

    // Run the category seeder
    (new SiteCategorySeeder)->run();

    // Assert each site has a category assigned
    expect(Site::where('tenant_id', $tenant->id)->whereNull('site_category_id')->count())->toBe(0);

    // Ensure tenant ids match for categories created
    $tenantIds = SiteCategory::query()->pluck('tenant_id')->unique()->all();
    expect($tenantIds)->toBe([$tenant->id]);

    // Ensure category->sites includes all sites
    $siteIds = Site::query()->pluck('id')->sort()->values()->all();
    $categorySiteIds = SiteCategory::with('sites')->get()->flatMap->sites->pluck('id')->sort()->values()->all();
    expect($categorySiteIds)->toEqual($siteIds);
});
