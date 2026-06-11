<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (\App\Models\Testimonial::with('user')->get() as $t) {
    echo "Testimonial ID: " . $t->id 
        . " | User: " . ($t->user?->name ?? 'None') 
        . " | Avatar path in DB: " . ($t->user?->avatar ?? 'None') 
        . " | Resolved URL: " . ($t->user?->getAvatarUrl() ?? 'None') 
        . PHP_EOL;
}
