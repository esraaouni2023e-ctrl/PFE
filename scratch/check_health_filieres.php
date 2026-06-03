<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$filieres = App\Models\Filiere::where('nom_filiere', 'like', '%Médecine%')
    ->orWhere('nom_filiere', 'like', '%Kiné%')
    ->orWhere('nom_filiere', 'like', '%Infirm%')
    ->get();

foreach ($filieres as $f) {
    echo $f->nom_filiere . ' | ' . $f->domaine . ' | ' . $f->type_bac . "\n";
}
