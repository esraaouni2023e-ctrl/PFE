<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\SiaepiRecommendationEngine;

$engine = new SiaepiRecommendationEngine();

$testCases = [
    'A' => [
        'label' => 'Excellent académique, Faible vocation prestige (Médecine)',
        'profil' => [
            'score_fg' => 185.0,
            'section_bac' => 'Mathématiques',
            'vecteur_psychometrique' => ['R'=>0.2,'I'=>0.3,'A'=>0.2,'S'=>0.4,'E'=>0.2,'C'=>0.3], // Faible I (Recherche/Médical)
            'gatb_scores' => ['G'=>18,'V'=>16,'N'=>19,'S'=>15],
            'big_five' => ['O'=>0.2,'C'=>0.8,'E'=>0.2,'A'=>0.7,'N'=>0.1],
            'valeurs' => ['Sec'=>0.8,'Ach'=>0.3,'Ben'=>0.9,'Aut'=>0.2],
            'interests' => ['MED' => 0.1], // Très faible intérêt médecine
            'sem' => 0.25
        ]
    ],
    'B' => [
        'label' => 'Forte vocation, Faible score académique (Ingénierie)',
        'profil' => [
            'score_fg' => 110.0,
            'section_bac' => 'Technique',
            'vecteur_psychometrique' => ['R'=>0.9,'I'=>0.8,'A'=>0.2,'S'=>0.3,'E'=>0.4,'C'=>0.5], 
            'gatb_scores' => ['G'=>10,'V'=>9,'N'=>11,'S'=>12],
            'big_five' => ['O'=>0.7,'C'=>0.9,'E'=>0.5,'A'=>0.5,'N'=>0.3],
            'valeurs' => ['Sec'=>0.5,'Ach'=>0.9,'Ben'=>0.4,'Aut'=>0.6],
            'interests' => ['ENG' => 0.9],
            'sem' => 0.28
        ]
    ],
    'C' => [
        'label' => 'Hybride (Science + Arts)',
        'profil' => [
            'score_fg' => 145.0,
            'section_bac' => 'Sciences expérimentales',
            'vecteur_psychometrique' => ['R'=>0.4,'I'=>0.7,'A'=>0.8,'S'=>0.5,'E'=>0.4,'C'=>0.3], 
            'gatb_scores' => ['G'=>14,'V'=>15,'N'=>12,'S'=>16],
            'big_five' => ['O'=>0.9,'C'=>0.6,'E'=>0.7,'A'=>0.6,'N'=>0.4],
            'valeurs' => ['Sec'=>0.4,'Ach'=>0.7,'Ben'=>0.5,'Aut'=>0.9],
            'interests' => ['ARCHI' => 0.8, 'MED' => 0.4],
            'sem' => 0.32
        ]
    ],
    'D' => [
        'label' => 'Indécis (High SEM)',
        'profil' => [
            'score_fg' => 130.0,
            'section_bac' => 'Économie et gestion',
            'vecteur_psychometrique' => ['R'=>0.5,'I'=>0.5,'A'=>0.5,'S'=>0.5,'E'=>0.5,'C'=>0.5], 
            'gatb_scores' => ['G'=>10,'V'=>10,'N'=>10,'S'=>10],
            'big_five' => ['O'=>0.5,'C'=>0.5,'E'=>0.5,'A'=>0.5,'N'=>0.5],
            'valeurs' => ['Sec'=>0.5,'Ach'=>0.5,'Ben'=>0.5,'Aut'=>0.5],
            'interests' => [],
            'sem' => 0.55 // High uncertainty
        ]
    ]
];

foreach ($testCases as $key => $case) {
    echo "\n=== TEST CASE $key : {$case['label']} ===\n";
    $results = $engine->recommend($case['profil'], 5);
    
    if (isset($results['error'])) {
        echo "Error: " . $results['error'] . "\n";
        continue;
    }

    foreach ($results['recommandations'] as $rec) {
        printf("[%d] %-40s | Score: %.4f | Voc: %.4f | Acad: %.4f | Risk: %.2f | Conf: %.2f | %s\n",
            $rec['Rang'],
            mb_substr($rec['Nom_Filiere'], 0, 40),
            $rec['Score_Final'],
            $rec['Score_RIASEC'],
            $rec['Score_Academique'],
            $rec['Penalite_Cognitive'], // Using this for debug as Risk is internal but impacts Score_Final
            $rec['Confidence'],
            $rec['is_exploratory'] ? 'EXPLORATORY' : 'STABLE'
        );
    }
}
