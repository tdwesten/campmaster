<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Seed the tenants table with Camping de nachtegaal.
     */
    public function run(): void
    {
        // Create Camping de nachtegaal tenant
        Tenant::create([
            'name' => 'Camping de nachtegaal',
        ]);

    }
}
