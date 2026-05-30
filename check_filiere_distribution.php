<?php
require __DIR__ . '/vendor/autoload.php';

// Boot minimal Laravel framework
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Filiere;

echo "Total row count in filieres table: " . Filiere::count() . "\n\n";

echo "Domain breakdown:\n";
$breakdown = Filiere::select('domaine', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
    ->groupBy('domaine')
    ->get();

foreach ($breakdown as $row) {
    echo "- " . ($row->domaine ?? 'NULL') . ": " . $row->count . "\n";
}

echo "\nFirst 5 filieres:\n";
$first5 = Filiere::limit(5)->get()->toArray();
print_r($first5);
