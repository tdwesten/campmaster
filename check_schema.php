<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$columns = \Illuminate\Support\Facades\Schema::getColumnListing('settings');

echo "Columns in settings table:\n";
print_r($columns);

$indexes = \Illuminate\Support\Facades\DB::select('PRAGMA index_list(settings)');

echo "\nIndexes in settings table:\n";
print_r($indexes);
