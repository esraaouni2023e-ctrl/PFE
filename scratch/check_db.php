<?php
require_once 'c:/laragon/www/pfe/vendor/autoload.php';
$app = require_once 'c:/laragon/www/pfe/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Filiere;
use App\Models\FiliereProfile;
use App\Models\ProfileRiasec;
use App\Models\Profile;
use App\Services\SiaepiRecommendationEngine;

echo "Filiere count: " . Filiere::count() . "\n";
echo "FiliereProfile count: " . FiliereProfile::count() . "\n";

$firstFive = Filiere::with('profile')->take(5)->get();
foreach ($firstFive as $i => $f) {
    echo "Filiere #$i: " . $f->nom_filiere . " (Code: " . $f->code_filiere . ")\n";
    if ($f->profile) {
        echo "  - Profile loaded: " . json_encode($f->profile->getRiasecVector()) . "\n";
    } else {
        echo "  - Profile is NULL!\n";
    }
}

// Let's find a real student in DB
$userRiasec = ProfileRiasec::first();
if ($userRiasec) {
    echo "Found student RIASEC for User ID: " . $userRiasec->user_id . "\n";
    echo "Raw DB scores: R=" . $userRiasec->score_r . ", I=" . $userRiasec->score_i . ", A=" . $userRiasec->score_a . ", S=" . $userRiasec->score_s . ", E=" . $userRiasec->score_e . ", C=" . $userRiasec->score_c . "\n";
    $academicProfile = Profile::where('user_id', $userRiasec->user_id)->first();
    $scoreFg = $academicProfile?->score_fg ?? 120;
    $sectionBac = $academicProfile?->section_bac ?? 'Informatique';
    $codeHolland = $userRiasec->code_holland ?? 'ISA';

    $maxScore = 100;
    $vecteurRiasec = [
        'R' => round($userRiasec->score_r / $maxScore, 4),
        'I' => round($userRiasec->score_i / $maxScore, 4),
        'A' => round($userRiasec->score_a / $maxScore, 4),
        'S' => round($userRiasec->score_s / $maxScore, 4),
        'E' => round($userRiasec->score_e / $maxScore, 4),
        'C' => round($userRiasec->score_c / $maxScore, 4),
    ];

    $profilEtudiant = [
        'id'                       => $userRiasec->user_id,
        'sem'                      => 0.15,
        'score_fg'                 => (float) $scoreFg,
        'section_bac'              => $sectionBac,
        'filiere_etudiant_actuelle'=> $sectionBac,
        'vecteur_psychometrique'   => $vecteurRiasec,
        'gatb_scores'              => ['GATB_G' => 0, 'GATB_V' => 0, 'GATB_N' => 0, 'GATB_S' => 0], // Empty GATB
        'code_holland'             => $codeHolland,
    ];

    $engine = new SiaepiRecommendationEngine();
    
    // Let's print calculations manually for the filieres to see what is happening.
    $filieres = $engine->loadFilieres();
    $studentRiasec = [
        'R' => 0.8,
        'I' => 0.2,
        'A' => 0.3,
        'S' => 0.9,
        'E' => 0.1,
        'C' => 0.5
    ];
    
    $riasecStudentNorm = [];
    foreach ($studentRiasec as $k => $v) {
        $rawV = $v <= 1.0 ? $v * 100.0 : $v;
        $riasecStudentNorm[$k] = 1.0 / (1.0 + exp(-(($rawV - 50.0) / 15.0)));
    }
    
    echo "Student Normalized RIASEC: " . json_encode($riasecStudentNorm) . "\n\n";

    $count = 0;
    foreach ($filieres as $f) {
        if (!in_array($f['Code_Filiere'], ['MAT095', 'MAT026', 'MAT002'])) {
            continue;
        }
        
        $filiereRiasecVec = [
            'R' => $f['riasec_r'] ?? 0.5,
            'I' => $f['riasec_i'] ?? 0.5,
            'A' => $f['riasec_a'] ?? 0.5,
            'S' => $f['riasec_s'] ?? 0.5,
            'E' => $f['riasec_e'] ?? 0.5,
            'C' => $f['riasec_c'] ?? 0.5,
        ];
        
        $riasecFiliereNorm = [];
        foreach ($filiereRiasecVec as $k => $v) {
            $rawV = $v * 100.0;
            $riasecFiliereNorm[$k] = 1.0 / (1.0 + exp(-(($rawV - 50.0) / 15.0)));
        }
        
        // Cosine Similarity
        $dot = 0.0; $normA = 0.0; $normB = 0.0;
        foreach ($riasecStudentNorm as $k => $a) {
            $b = $riasecFiliereNorm[$k] ?? 0.0;
            $dot += $a * $b;
            $normA += $a * $a;
            $normB += $b * $b;
        }
        $cosine = ($normA < 1e-6 || $normB < 1e-6) ? 0.5 : ($dot / (sqrt($normA) * sqrt($normB)));
        $mappedCosine = 0.5 * ($cosine + 1.0);
        
        $vocationScore = 0.7 * $mappedCosine + 0.3 * 0.5; // interestOverlap fallback is 0.5
        
        echo "Filiere: " . $f['Nom_Filiere'] . " (" . $f['Code_Filiere'] . ")\n";
        echo "  - Raw RIASEC Vector: " . json_encode($filiereRiasecVec) . "\n";
        echo "  - Normalized RIASEC Vector: " . json_encode($riasecFiliereNorm) . "\n";
        echo "  - Cosine Sim: $cosine\n";
        echo "  - Mapped Cosine: $mappedCosine\n";
        echo "  - Vocation Score: $vocationScore\n\n";
    }
} else {
    echo "No student RIASEC found in DB!\n";
}
