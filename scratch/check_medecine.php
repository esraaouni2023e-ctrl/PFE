<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

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

$engine = new SiaepiRecommendationEngine();
$filieres = $engine->loadFilieres();

echo "Running scoring pipeline details for Medicine filieres:\n\n";

$refEngine = new ReflectionClass(SiaepiRecommendationEngine::class);
$runScoringPipeline = $refEngine->getMethod('runScoringPipeline');
$runScoringPipeline->setAccessible(true);

$gatbRaw = $fullProfile['gatb_scores'];
$scored = $runScoringPipeline->invoke($engine, $fullProfile, $filieres, 'scientific', (float)$fullProfile['score_fg'], $gatbRaw, false);

foreach ($scored as $item) {
    if (strpos($item['Nom_Filiere'], 'Médecine') !== false || strpos($item['Nom_Filiere'], 'Kinésithérapie') !== false || strpos($item['Nom_Filiere'], 'Sciences Infirmières') !== false) {
        echo "ID: {$item['filiere_id']} | {$item['Nom_Filiere']} | Bac: {$item['Type_Bac']} | RIASEC: {$item['Code_RIASEC']}\n";
        echo "  VocationScore: {$item['VocationScore']}\n";
        echo "  DomainScore:   {$item['DomainScore']}\n";
        echo "  CognitiveScore:{$item['CognitiveScore']}\n";
        echo "  AccessScore:   {$item['AccessScore']}\n";
        echo "  FinalScore:    {$item['FinalScore']}\n\n";
    }
}
