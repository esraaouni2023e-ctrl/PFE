<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "--- Column Listing for filieres ---\n";
print_r(Schema::getColumnListing('filieres'));

echo "--- Column Listing for recommendations ---\n";
print_r(Schema::getColumnListing('recommendations'));
