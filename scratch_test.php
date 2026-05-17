<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\SiaepiRecommendationEngine;

$engine = new SiaepiRecommendationEngine();

// Profil 1: Excellence Informatique
$profil1 = [
    'score_fg' => 170, // Excellence
    'section_bac' => 'Informatique',
    'vecteur_psychometrique' => ['R'=>0.9, 'I'=>0.9, 'A'=>0.2, 'S'=>0.2, 'E'=>0.4, 'C'=>0.8], // Très fort IRC
    'gatb_scores' => ['G'=>18, 'V'=>12, 'N'=>18, 'S'=>16], // Excellent en logique et calcul
    'code_holland' => 'IRC',
    'texte_psycho' => 'informatique programmation algorithme logiciel',
];

// Profil 2: Difficulté Sciences
$profil2 = [
    'score_fg' => 90, // Difficulté
    'section_bac' => 'Sciences',
    'vecteur_psychometrique' => ['R'=>0.4, 'I'=>0.5, 'A'=>0.8, 'S'=>0.8, 'E'=>0.7, 'C'=>0.3], // Vise l'art/social mais en sciences
    'gatb_scores' => ['G'=>8, 'V'=>10, 'N'=>8, 'S'=>8], // Faible
    'code_holland' => 'SAE',
    'texte_psycho' => 'art design aide social communication',
];

echo "--- PROFIL 1 (Excellence Informatique, IRC) ---\n";
$r1 = $engine->recommend($profil1, 5);
foreach($r1['recommandations'] as $r) {
    $pareto = !empty($r['is_pareto_optimal']) ? '[PARETO]' : '';
    echo "{$r['Nom_Filiere']} | {$r['Score_Final']} | RIASEC: {$r['Code_RIASEC']} {$pareto}\n";
    echo "  XAI: " . json_encode($r['Explication']) . "\n";
}

echo "\n--- PROFIL 2 (Difficulté Sciences, SAE) ---\n";
$r2 = $engine->recommend($profil2, 5);
foreach($r2['recommandations'] as $r) {
    $pareto = !empty($r['is_pareto_optimal']) ? '[PARETO]' : '';
    echo "{$r['Nom_Filiere']} | {$r['Score_Final']} | RIASEC: {$r['Code_RIASEC']} {$pareto}\n";
    echo "  XAI: " . json_encode($r['Explication']) . "\n";
}
