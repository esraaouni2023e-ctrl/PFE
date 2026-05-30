<?php
require __DIR__ . '/vendor/autoload.php';

// Boot minimal Laravel framework
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Filiere;
use Illuminate\Support\Facades\Schema;

echo "Columns in filieres table:\n";
print_r(Schema::getColumnListing('filieres'));

echo "\nDistinct domains in filieres table:\n";
$domains = Filiere::select('domaine')->distinct()->pluck('domaine')->toArray();
print_r($domains);

echo "\nDistinct Type_Bac in filieres table:\n";
$typeBacs = Filiere::select('type_bac')->distinct()->pluck('type_bac')->toArray();
print_r($typeBacs);
