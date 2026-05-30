<?php

require_once 'c:/laragon/www/pfe/vendor/autoload.php';
$app = require_once 'c:/laragon/www/pfe/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Profile;
use App\Models\ProfileRiasec;
use App\Services\RIASEC\AdaptiveTestEngine;

echo "--- ALL USERS IN DB ---\n";
$users = User::all();
foreach ($users as $u) {
    $hasProfile = Profile::where('user_id', $u->id)->exists() ? 'YES' : 'NO';
    $hasRiasec = ProfileRiasec::pourUser($u->id)->complets()->exists() ? 'YES' : 'NO';
    echo "User ID: {$u->id} | Name: {$u->name} | Email: {$u->email} | Has Profile: {$hasProfile} | Has RIASEC: {$hasRiasec}\n";
}

echo "\n--- PROFILES DETAILS ---\n";
$profiles = Profile::all();
foreach ($profiles as $p) {
    echo "Profile User ID: {$p->user_id} | FG: {$p->score_fg} | Bac: {$p->section_bac} | Notes keys: " . implode(', ', array_keys($p->notes_matieres ?? [])) . "\n";
}

echo "\n--- RIASEC PROFILES DETAILS ---\n";
$riasecs = ProfileRiasec::complets()->get();
foreach ($riasecs as $r) {
    echo "Riasec ID: {$r->id} | User ID: {$r->user_id} | Code: {$r->code_holland} | Session ID: {$r->test_session_id}\n";
    $adaptiveEngine = new AdaptiveTestEngine();
    $catState = $adaptiveEngine->getSessionState($r->test_session_id);
    echo " - Dimensions scores:\n";
    if (isset($catState['dimensions'])) {
        foreach ($catState['dimensions'] as $dim => $st) {
            echo "   * {$dim}: " . ($st['score'] ?? 0.0) . "\n";
        }
    } else {
        echo "   * No dimensions found in CAT state!\n";
    }
}
