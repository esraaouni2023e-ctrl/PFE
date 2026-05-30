<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$basePath = storage_path('app/excels/');
$files = glob($basePath . '*.xlsx');
foreach ($files as $file) {
    echo basename($file) . PHP_EOL;
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
    $ws = $spreadsheet->getActiveSheet();
    $rows = $ws->toArray();
    echo json_encode(array_slice($rows, 0, 1)) . PHP_EOL;
}
