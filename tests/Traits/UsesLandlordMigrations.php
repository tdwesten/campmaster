<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Artisan;

trait UsesLandlordMigrations
{
    public function runLandlordMigrations()
    {
        Artisan::call('migrate', [
            '--path' => 'database/migrations/landlord',
            '--database' => config('database.default'),
        ]);
    }
}
