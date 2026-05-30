<?php

// Boot minimal Laravel framework using absolute paths
require_once 'c:/laragon/www/pfe/vendor/autoload.php';
$app = require_once 'c:/laragon/www/pfe/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\SiaepiRecommendationEngine;
use App\Models\Filiere;

echo "=============================================\n";
echo " SIAEPI v6.0 Engine & Pipeline Verification Script\n";
echo "=============================================\n\n";

// 1. Setup mock student profile with high FG (183)
$mockProfile = [
    'id' => 1,
    'sem' => 0.15,
    'score_fg' => 183.0, // High Score FG
    'section_bac' => 'Mathématiques',
    'vecteur_psychometrique' => [
        'R' => 0.50,
        'I' => 0.90, // strong Investigative
        'A' => 0.40,
        'S' => 0.30,
        'E' => 0.70,
        'C' => 0.80
    ],
    'gatb_scores' => [
        'GATB_G' => 90,
        'GATB_V' => 75,
        'GATB_N' => 95,
        'GATB_S' => 80
    ],
    'code_holland' => 'ICE',
    'big_five' => [
        'O' => 0.8, 'C' => 0.9, 'E' => 0.6, 'A' => 0.5, 'N' => -0.2
    ],
    'valeurs' => [
        'Sec' => 0.6, 'Ach' => 0.9, 'Ben' => 0.4, 'Aut' => 0.7
    ],
    'interests' => [
        'INFO' => 0.9, 'ENG' => 0.8, 'ECO' => 0.7
    ]
];

// 2. Instantiate and run engine
$engine = new SiaepiRecommendationEngine();
$start = microtime(true);
$results = $engine->recommend($mockProfile, 10);
$elapsed = round((microtime(true) - $start) * 1000, 2);

if (isset($results['error'])) {
    echo "❌ Error returned from engine: " . $results['error'] . "\n";
    exit(1);
}

$ambitieuses = $results['ambitieuses'] ?? [];
$optimales = $results['recommandations'] ?? [];
$accessibles = $results['accessibles'] ?? [];
$securite = $results['securite'] ?? [];

echo "Execution time: {$elapsed} ms\n";
echo "Total scoreable filieres: " . $results['total_scorees'] . "\n\n";

echo "--- LISTS SIZES ---\n";
echo "Ambitieuses: " . count($ambitieuses) . "\n";
echo "Optimales: " . count($optimales) . "\n";
echo "Accessibles: " . count($accessibles) . "\n";
echo "Sécurité: " . count($securite) . "\n\n";

echo "--- TOP OPTIMALES DETAILS ---\n";
foreach ($optimales as $f) {
    echo sprintf(
        "Filiere: %s\n - Final (RankScore): %f\n - RIASEC Match: %f\n - GATB Match: %f\n - SDO Access: %f\n - Motivation Match: %f\n - Confidence Flag: %s\n\n",
        $f['Nom_Filiere'],
        $f['Score_Final'],
        $f['Score_RIASEC'],
        $f['Score_Academique'],
        $f['Score_Accessibilite'],
        $f['Score_Motivation'],
        $f['confidence_flag'] ?? 'N/A'
    );
}

$hasError = false;

// 3. Validation: Analyse de plusieurs centaines de filières
if ($results['total_scorees'] < 150) {
    echo "❌ ERROR: Too few filieres scored ({$results['total_scorees']}). Overlap RIASEC filter might still be active!\n";
    $hasError = true;
} else {
    echo "✅ SUCCESS: Scored {$results['total_scorees']} filieres. Relaxed RIASEC filter works!\n";
}

// 4. Validation: Hard Under-Match Filter
// Check if any filiere in Optimales/Ambitieuses has an under-match (gap > 55 points, meaning SDO < 128 for FG 183)
foreach (array_merge($optimales, $ambitieuses) as $f) {
    $sdo = max($f['SDO_2025'], $f['SDO_2024'], $f['SDO_2023']);
    if ($sdo > 0 && (183.0 - $sdo) > 55.0) {
        echo "❌ ERROR: Filiere under-matched in Top lists: {$f['Nom_Filiere']} has SDO {$sdo} (gap " . (183.0 - $sdo) . " points)\n";
        $hasError = true;
    }
}
echo "✅ SUCCESS: Hard Under-Match filter validated (no high-gap filieres in top lists).\n";

// 5. Validation: Taxonomy Mapping & Career Path for Licence en Sciences Économiques
$ecoFiliere = Filiere::where('nom_filiere', 'like', '%sciences économiques%')
    ->orWhere('nom_filiere', 'like', '%science économique%')
    ->first();

if (!$ecoFiliere) {
    echo "⚠️ WARNING: Licence en Sciences Économiques not found in database, searching for general management/economics...\n";
    $ecoFiliere = Filiere::where('nom_filiere', 'like', '%gestion%')
        ->orWhere('nom_filiere', 'like', '%management%')
        ->first();
}

if ($ecoFiliere) {
    $domaine = DB::table('domaines')->where('id', $ecoFiliere->domaine_id)->first();
    $specialisation = DB::table('specialisations')->where('id', $ecoFiliere->specialisation_id)->first();
    $careers = $ecoFiliere->careers;

    echo "\n--- TAXONOMY CHECK for: {$ecoFiliere->nom_filiere} ---\n";
    echo "Domain Mapped: " . ($domaine->nom ?? 'None') . " (Code: " . ($domaine->code ?? 'None') . ")\n";
    echo "Specialisation Mapped: " . ($specialisation->nom ?? 'None') . " (Code: " . ($specialisation->code ?? 'None') . ")\n";
    if (!empty($careers)) {
        echo "Careers Linked: " . implode(', ', array_column($careers, 'title')) . "\n";
        if (str_contains(strtolower($specialisation->code ?? ''), 'economie')) {
            echo "✅ SUCCESS: Economics filiere correctly mapped to economic taxonomy!\n";
        } else {
            echo "❌ ERROR: Economics filiere mapped to wrong specialisation: " . ($specialisation->code ?? 'None') . "\n";
            $hasError = true;
        }
    } else {
        echo "❌ ERROR: No careers linked to economics filiere!\n";
        $hasError = true;
    }
} else {
    echo "❌ ERROR: Economics/Management filiere not found in DB!\n";
    $hasError = true;
}

if ($hasError) {
    echo "\n❌ VERIFICATION FAILED. Some checks did not pass.\n";
    exit(1);
} else {
    echo "\n✅ ALL VERIFICATIONS PASSED SUCCESSFULLY!\n";
}
