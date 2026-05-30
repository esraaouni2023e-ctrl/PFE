<?php

require_once 'c:/laragon/www/pfe/vendor/autoload.php';
$app = require_once 'c:/laragon/www/pfe/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Profile;
use App\Models\ProfileRiasec;
use App\Services\SiaepiRecommendationEngine;

$user = User::where('name', 'test')->first();
$profile = Profile::where('user_id', $user->id)->first();
$profilRiasec = ProfileRiasec::pourUser($user->id)->complets()->recents()->first();

$vecteurRiasec = [
    'R' => round((float)$profilRiasec->score_r / 100, 4),
    'I' => round((float)$profilRiasec->score_i / 100, 4),
    'A' => round((float)$profilRiasec->score_a / 100, 4),
    'S' => round((float)$profilRiasec->score_s / 100, 4),
    'E' => round((float)$profilRiasec->score_e / 100, 4),
    'C' => round((float)$profilRiasec->score_c / 100, 4),
];

$adaptiveEngine = new \App\Services\RIASEC\AdaptiveTestEngine();
$catState = $adaptiveEngine->getSessionState($profilRiasec->test_session_id);

$fullProfile = [
    'id'                     => $user->id,
    'sem'                    => 0.30,
    'score_fg'               => $profile->score_fg,
    'section_bac'            => $profile->section_bac,
    'filiere_etudiant_actuelle' => $profile->section_bac,
    'vecteur_psychometrique' => $vecteurRiasec,
    'gatb_scores'            => ['G'=>60,'V'=>60,'N'=>60,'S'=>60],
    'code_holland'           => $profilRiasec->code_holland,
    'notes_matieres'         => $profile->notes_matieres ?? [],
    'big_five' => [
        'O' => 0.0, 'C' => 0.0, 'E' => 0.0, 'A' => 0.0, 'N' => 0.0
    ],
    'valeurs' => [
        'Sec' => 0.0, 'Ach' => 0.0, 'Ben' => 0.0, 'Aut' => 0.0
    ],
    'interests' => [
        'MED'   => 0.0,
        'ENG'   => 0.0,
        'INFO'  => 0.0,
        'declared' => $profile->interests ?? '',
    ]
];

$engine = new SiaepiRecommendationEngine();
$results = $engine->recommend($fullProfile, 100);

$buckets = ['recommandations', 'accessibles', 'ambitieuses', 'securite'];
foreach ($buckets as $b) {
    echo "=== BUCKET: $b ===\n";
    foreach ($results[$b] ?? [] as $f) {
        if (str_contains(strtolower($f['Nom_Filiere']), 'kiné') || str_contains(strtolower($f['Nom_Filiere']), 'infirm')) {
            $sdo = max(($f['SDO_2025'] ?? 0), ($f['SDO_2024'] ?? 0), ($f['SDO_2023'] ?? 0));
            $gap = $fullProfile['score_fg'] - $sdo;
            echo sprintf(
                " - %s (Code: %s) | SDO: %.1f | Gap: %.1f | FinalScore: %.4f | Type_Bac: %s\n",
                $f['Nom_Filiere'],
                $f['Code_Filiere'],
                $sdo,
                $gap,
                $f['Score_Final'],
                $f['Type_Bac']
            );
        }
    }
}
