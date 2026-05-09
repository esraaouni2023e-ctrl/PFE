<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
echo "DB::connection()->getDatabaseName(): " . \DB::connection()->getDatabaseName() . "\n";
echo "env(DB_DATABASE): " . env('DB_DATABASE') . "\n";
