<?php
require __DIR__.'/../../vendor/autoload.php';
$app = require __DIR__.'/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$has = \Schema::hasColumn('users', 'role');
echo "has_role: ".($has ? 'yes' : 'no')."\n";

$cols = \DB::select('SHOW COLUMNS FROM users');
foreach ($cols as $c) {
    echo $c->Field.' '. $c->Type ."\n";
}
