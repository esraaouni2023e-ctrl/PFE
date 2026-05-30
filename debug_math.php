<?php
 
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
 
use App\Services\SiaepiRecommendationEngine;
 
$engine = new SiaepiRecommendationEngine();
 
$profilExcellentMath = [
    'score_fg'                  => 180.0,
    'section_bac'               => 'Mathématiques',
    'filiere_etudiant_actuelle' => 'Mathématiques',
    'texte_psycho'              => 'analyse logique mathématique recherche sciences',
    'vecteur_psychometrique'    => [
        'R' => 0.5,
        'I' => 0.9,
        'A' => 0.4,
        'S' => 0.3,
        'E' => 0.6,
        'C' => 0.8,
    ],
    'gatb_scores' => [
        'G' => 95,
        'V' => 80,
        'N' => 98,
        'S' => 90,
    ],
    'code_holland' => 'ICA',
    'big_five' => [
        'O' => 0.8,
        'C' => 0.9,
        'E' => 0.6,
        'A' => 0.5,
        'N' => 0.2,
    ],
    'valeurs' => [
        'Sec' => 0.6,
        'Ach' => 0.9,
        'Ben' => 0.5,
        'Aut' => 0.8,
    ],
    'interests' => [
        'MED'   => 0.2,
        'ENG'   => 0.9,
        'INFO'  => 0.9,
        'DROIT' => 0.3,
        'ECO'   => 0.6,
        'EDU'   => 0.4,
        'ART'   => 0.3,
        'LTR'   => 0.2,
        'SOC'   => 0.3,
        'SPO'   => 0.2,
        'ARCHI' => 0.5,
    ]
];
 
$res = $engine->recommend($profilExcellentMath, 6);
echo "=== RECOMMENDATIONS ===\n";
foreach ($res['recommandations'] as $f) {
    echo "Filiere: " . $f['Nom_Filiere'] . " | Domain: " . $f['Domaine'] . " | FinalScore: " . $f['FinalScore'] . " | AccessScore: " . $f['AccessScore'] . " | VocationScore: " . $f['VocationScore'] . "\n";
}
