<?php

require_once 'c:/laragon/www/pfe/vendor/autoload.php';
$app = require_once 'c:/laragon/www/pfe/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Profile;
use App\Models\ProfileRiasec;
use App\Services\RIASEC\AdaptiveTestEngine;
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
        'O' => $catState['dimensions']['B5_O']['score'] ?? $catState['dimensions']['O']['score'] ?? 0.0,
        'C' => $catState['dimensions']['B5_C']['score'] ?? $catState['dimensions']['C']['score'] ?? 0.0,
        'E' => $catState['dimensions']['B5_E']['score'] ?? $catState['dimensions']['E']['score'] ?? 0.0,
        'A' => $catState['dimensions']['B5_A']['score'] ?? $catState['dimensions']['A']['score'] ?? 0.0,
        'N' => $catState['dimensions']['B5_N']['score'] ?? $catState['dimensions']['N']['score'] ?? 0.0,
    ],
    'valeurs' => [
        'Sec' => $catState['dimensions']['Sec']['score'] ?? 0.0,
        'Ach' => $catState['dimensions']['Ach']['score'] ?? 0.0,
        'Ben' => $catState['dimensions']['Ben']['score'] ?? 0.0,
        'Aut' => $catState['dimensions']['Aut']['score'] ?? 0.0,
    ],
    'interests' => [
        'MED'   => $catState['dimensions']['MED']['score'] ?? 0.0,
        'ENG'   => $catState['dimensions']['ENG']['score'] ?? 0.0,
        'INFO'  => $catState['dimensions']['INFO']['score'] ?? 0.0,
        'DROIT' => $catState['dimensions']['DROIT']['score'] ?? 0.0,
        'ECO'   => $catState['dimensions']['ECO']['score'] ?? 0.0,
        'EDU'   => $catState['dimensions']['EDU']['score'] ?? 0.0,
        'ART'   => $catState['dimensions']['ART']['score'] ?? 0.0,
        'LTR'   => $catState['dimensions']['LTR']['score'] ?? 0.0,
        'SOC'   => $catState['dimensions']['SOC']['score'] ?? 0.0,
        'SPO'   => $catState['dimensions']['SPO']['score'] ?? 0.0,
        'ARCHI' => $catState['dimensions']['ARCHI']['score'] ?? 0.0,
        'declared' => $profile->interests ?? '',
    ]
];

echo "Student MED Interest: " . $fullProfile['interests']['MED'] . "\n";
echo "Student Section Bac: " . $fullProfile['section_bac'] . "\n\n";

$engine = new SiaepiRecommendationEngine();
$results = $engine->recommend($fullProfile, 8);

echo "--- RESULTS ---\n";
echo "Total scoreable filieres: " . $results['total_scorees'] . "\n";
echo "Top Recommandations count: " . count($results['recommandations']) . "\n";
foreach ($results['recommandations'] as $r) {
    echo "Filiere (Top): {$r['Nom_Filiere']} | Domain: {$r['Domaine']} | Score: {$r['Score_Final']}\n";
}

echo "\nFilières de repli count: " . count($results['securite']) . "\n";
foreach ($results['securite'] as $r) {
    echo "Filiere (Repli): {$r['Nom_Filiere']} | Domain: {$r['Domaine']} | Score: {$r['Score_Final']}\n";
}
