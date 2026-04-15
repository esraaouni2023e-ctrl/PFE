<?php
require __DIR__.'/../../vendor/autoload.php';
$app = require __DIR__.'/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$u = User::create([
    'name' => 'role_test',
    'email' => 'role_test+' . time() . '@example.com',
    'password' => Hash::make('password'),
    'role' => User::ROLE_COUNSELOR,
]);

echo "Created user id: {$u->id} role: {$u->role}\n";
