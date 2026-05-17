<?php

namespace App\Http\Controllers;

use App\Models\AnswerRiasec;
use App\Models\Profile;
use App\Models\ProfileRiasec;
use App\Models\QuestionRiasec;
use App\Services\AdmissionPredictorService;
use App\Services\SiaepiRecommendationEngine;
use App\Services\RIASEC\GatbCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected AdmissionPredictorService $predictor;

    public function __construct(AdmissionPredictorService $predictor)
    {
        $this->predictor = $predictor;
    }

    public function index()
    {
        $user = auth()->user();
        $studentName = $user ? $user->name : null;

        $profile = $user->profile;
        $portfolios = $user->portfolioItems()->latest()->get();
        $roadmaps = $user->careerRoadmaps()->latest()->get();

        // Récupération du dernier profil de l'étudiant (complet ou en cours)
        $profilRiasec = \App\Models\ProfileRiasec::pourUser($user->id)
            ->complets()
            ->recents()
            ->first();

        $dynamicSkills = [];
        $profilIaScore = 78;

        if ($profilRiasec) {
            $profilIaScore = round($profilRiasec->score_coherence ?? 85);

            // Extraction des 3 meilleures dimensions RIASEC
            $scores = [
                'Réaliste'      => $profilRiasec->score_r,
                'Investigatif'  => $profilRiasec->score_i,
                'Artistique'    => $profilRiasec->score_a,
                'Social'        => $profilRiasec->score_s,
                'Entreprenant'  => $profilRiasec->score_e,
                'Conventionnel' => $profilRiasec->score_c,
            ];
            arsort($scores);
            $colors = ['var(--accent)', 'var(--accent2)', 'var(--accent3)', 'var(--gold)', 'var(--ink)'];
            $i = 0;
            foreach (array_slice($scores, 0, 3) as $label => $val) {
                $dynamicSkills[] = [
                    'label' => "Intérêt $label",
                    'val'   => min(100, round($val)),
                    'color' => $colors[$i++ % count($colors)]
                ];
            }

            // Extraction des scores réels GATB (v5.0 : GATB_G/V/N/S + rétrocompat G/V/Num/Sp)
            $sessionId = $profilRiasec->test_session_id;
            if ($sessionId) {
                // Utilise les colonnes calculées si disponibles
                $gScoreG = $profilRiasec->score_gatb_g ?? null;
                $gScoreN = $profilRiasec->score_gatb_n ?? null;

                if (is_null($gScoreG)) {
                    // Recalcul à la volée (rétrocompatibilité)
                    $rawGatbAnswers = \App\Models\AnswerRiasec::where('test_session_id', $sessionId)
                        ->whereHas('question', function ($q) {
                            $q->whereIn('dimension', ['GATB_G', 'GATB_V', 'GATB_N', 'GATB_S', 'G', 'V', 'Num', 'Sp']);
                        })
                        ->with('question')
                        ->get()
                        ->map(fn ($ans) => [
                            'dimension' => $ans->question->dimension,
                            'score'     => $ans->valeur,
                        ])->toArray();

                    if (!empty($rawGatbAnswers)) {
                        $gatbCalc = new \App\Services\RIASEC\GatbCalculator();
                        $gScores  = $gatbCalc->calculateScores($rawGatbAnswers);
                        $gScoreG  = $gScores['GATB_G'] ?? 0;
                        $gScoreN  = $gScores['GATB_N'] ?? 0;
                    }
                }

                if ($gScoreG > 0 || $gScoreN > 0) {
                    $dynamicSkills[] = [
                        'label' => 'Logique GATB (G)',
                        'val'   => min(100, (int) $gScoreG),
                        'color' => 'var(--accent2)'
                    ];
                    $dynamicSkills[] = [
                        'label' => 'Calcul GATB (N)',
                        'val'   => min(100, (int) $gScoreN),
                        'color' => 'var(--accent)'
                    ];
                }
            }
        } else {
            // Valeurs par défaut si le test n'est pas encore passé
            $dynamicSkills = [
                ['label'=>'Créativité',  'val'=>92, 'color'=>'var(--accent)'],
                ['label'=>'Logique',      'val'=>85, 'color'=>'var(--accent2)'],
                ['label'=>'Intérêt Tech', 'val'=>89, 'color'=>'var(--accent)'],
                ['label'=>'Social',       'val'=>64, 'color'=>'var(--accent3)'],
                ['label'=>'Gestion',      'val'=>71, 'color'=>'var(--gold)'],
            ];
        }

        // Formations de base (pour le MVP ou fallback)
        $formations = [
            ['icon'=>'🖥️','name'=>'Licence Informatique', 'univ'=>'ESPRIT – Tunis'],
            ['icon'=>'📊','name'=>'Master Data Science', 'univ'=>'ENSI – La Manouba'],
            ['icon'=>'🤖','name'=>'Ingénierie IA', 'univ'=>'SUP\'COM – Tunis'],
            ['icon'=>'🔒','name'=>'Cybersécurité', 'univ'=>'ISI – Tunis'],
            ['icon'=>'🌐','name'=>'Développement Web', 'univ'=>'ISET – Sfax'],
            ['icon'=>'📱','name'=>'Développement Mobile', 'univ'=>'ISIM – Monastir'],
        ];

        $predictions = $this->predictor->predictAdmissionChances($profile, $formations);

        // Si le test est terminé, on va chercher le vrai Top 6 de l'IA Python pour le Dashboard !
        if ($profilRiasec) {
            $academicProfile = $profile;
            $scoreFg         = $academicProfile?->score_fg ?? 120;
            $sectionBac      = $academicProfile?->section_bac ?? 'Informatique';
            $codeHolland     = $profilRiasec->code_holland ?? 'ISA';

            $maxScore = 100;
            $vecteurRiasec = [
                'R' => round($profilRiasec->score_r / $maxScore, 4),
                'I' => round($profilRiasec->score_i / $maxScore, 4),
                'A' => round($profilRiasec->score_a / $maxScore, 4),
                'S' => round($profilRiasec->score_s / $maxScore, 4),
                'E' => round($profilRiasec->score_e / $maxScore, 4),
                'C' => round($profilRiasec->score_c / $maxScore, 4),
            ];

            $sessionId     = $profilRiasec->test_session_id;
            $textoKeywords = $this->buildTextoFromAnswers($sessionId, $codeHolland, $sectionBac);

            // v5.0 : Utilise les colonnes pré-calculées du profil en priorité
            $gatbScores = [
                'GATB_G' => $profilRiasec->score_gatb_g ?? 0,
                'GATB_V' => $profilRiasec->score_gatb_v ?? 0,
                'GATB_N' => $profilRiasec->score_gatb_n ?? 0,
                'GATB_S' => $profilRiasec->score_gatb_s ?? 0,
                'TOTAL'  => 0,
            ];
            if (array_sum(array_values($gatbScores)) === 0 && $sessionId) {
                // Recalcul si colonnes vides (ancienne session)
                $rawGatbAnswers = \App\Models\AnswerRiasec::where('test_session_id', $sessionId)
                    ->whereHas('question', function ($q) {
                        $q->whereIn('dimension', ['GATB_G', 'GATB_V', 'GATB_N', 'GATB_S', 'G', 'V', 'Num', 'Sp']);
                    })
                    ->with('question')
                    ->get()
                    ->map(fn ($ans) => [
                        'dimension' => $ans->question->dimension,
                        'score'     => $ans->valeur,
                    ])->toArray();

                if (!empty($rawGatbAnswers)) {
                    $gatbCalc   = new \App\Services\RIASEC\GatbCalculator();
                    $gatbScores = $gatbCalc->calculateScores($rawGatbAnswers);
                }
            }
            $gatbScores['TOTAL'] = round(
                (($gatbScores['GATB_G'] ?? 0) + ($gatbScores['GATB_V'] ?? 0) +
                 ($gatbScores['GATB_N'] ?? 0) + ($gatbScores['GATB_S'] ?? 0)) / 4, 1
            );

            $profilEtudiant = [
                'score_fg'                 => (float) $scoreFg,
                'section_bac'              => $sectionBac,
                'filiere_etudiant_actuelle'=> $sectionBac,
                'texte_psycho'             => $textoKeywords,
                'vecteur_psychometrique'   => $vecteurRiasec,
                'gatb_scores'              => $gatbScores,
                'code_holland'             => $codeHolland,
            ];

            // On demande le Top 6 au moteur SIAEPI PHP-natif
            try {
                $engine = new \App\Services\SiaepiRecommendationEngine();
                $recs = $engine->recommend($profilEtudiant, 6);
                
                if (!isset($recs['error']) && !empty($recs['recommandations'])) {
                    $predictions = [];
                    foreach ($recs['recommandations'] as $r) {
                        $predictions[] = [
                            'icon'  => '🎯',
                            'name'  => $r['Nom_Filiere'] ?? 'Formation',
                            'univ'  => ($r['Etablissement'] ?? '') . ' – ' . ($r['Universite'] ?? ''),
                            'score' => isset($r['Score_Final_Contextuel']) ? round($r['Score_Final_Contextuel'] * 100) : (isset($r['Score_Final']) ? round($r['Score_Final'] * 100) : 80),
                            'code'  => $r['Code_Filiere'] ?? ''
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Erreur SIAEPI: " . $e->getMessage());
                // Silencieux pour le fallback MVP
            }
        }

        return view('student.dashboard', compact(
            'studentName',
            'profile',
            'portfolios',
            'roadmaps',
            'predictions',
            'profilRiasec',
            'dynamicSkills',
            'profilIaScore'
        ));
    }

    public function showRecommendations(Request $request)
    {
        $userId = Auth::id();

        // ── 1. Profil académique ─────────────────────────────────────────
        $academicProfile = Profile::where('user_id', $userId)->first();
        $scoreFg         = (float) ($academicProfile?->score_fg ?? 0);
        $sectionBac      = $academicProfile?->section_bac ?? '';

        // ── 2. Dernier profil RIASEC complété ────────────────────────────
        $profilRiasec = ProfileRiasec::pourUser($userId)
            ->complets()
            ->recents()
            ->first();

        $vecteurRiasec = ['R'=>0.5,'I'=>0.5,'A'=>0.5,'S'=>0.5,'E'=>0.5,'C'=>0.5];
        $codeHolland   = 'ISA';

        if ($profilRiasec) {
            $maxScore = max(1, $profilRiasec->score_r + $profilRiasec->score_i +
                                  $profilRiasec->score_a + $profilRiasec->score_s +
                                  $profilRiasec->score_e + $profilRiasec->score_c) / 6;
            $maxScore = max($maxScore, 1);
            // Normalise 0–1 basé sur le score max de l'étudiant
            $allScores = [
                'R' => (float)$profilRiasec->score_r,
                'I' => (float)$profilRiasec->score_i,
                'A' => (float)$profilRiasec->score_a,
                'S' => (float)$profilRiasec->score_s,
                'E' => (float)$profilRiasec->score_e,
                'C' => (float)$profilRiasec->score_c,
            ];
            $maxVal = max(array_values($allScores)) ?: 100;
            foreach ($allScores as $k => $v) {
                $vecteurRiasec[$k] = round($v / $maxVal, 4);
            }
            $codeHolland = $profilRiasec->code_holland;
        }

        // ── 3. Scores GATB réels (v5.0) ─────────────────────────────────
        $sessionId  = session('riasec_session_id') ?? $profilRiasec?->test_session_id;

        // Priorité aux colonnes pré-calculées du profil
        $gatbScores = [
            'GATB_G' => $profilRiasec?->score_gatb_g ?? 0,
            'GATB_V' => $profilRiasec?->score_gatb_v ?? 0,
            'GATB_N' => $profilRiasec?->score_gatb_n ?? 0,
            'GATB_S' => $profilRiasec?->score_gatb_s ?? 0,
        ];

        if (array_sum(array_values($gatbScores)) === 0 && $sessionId) {
            $rawGatbAnswers = AnswerRiasec::where('test_session_id', $sessionId)
                ->whereHas('question', fn ($q) => $q->whereIn('dimension', [
                    'GATB_G', 'GATB_V', 'GATB_N', 'GATB_S', 'G', 'V', 'Num', 'Sp'
                ]))
                ->with('question')->get()
                ->map(fn ($ans) => [
                    'dimension' => $ans->question->dimension,
                    'score'     => $ans->valeur,
                ])->toArray();

            if (!empty($rawGatbAnswers)) {
                $gatbCalc   = new GatbCalculator();
                $gatbScores = $gatbCalc->calculateScores($rawGatbAnswers);
            }
        }

        // ── 4. Appel au moteur SIAEPI PHP-natif ─────────────────────────
        $engine = new SiaepiRecommendationEngine();
        $adaptiveEngine = new \App\Services\RIASEC\AdaptiveTestEngine();
        
        // Récupération des dimensions complètes depuis l'état adaptatif
        $catState = $adaptiveEngine->getSessionState($sessionId);
        
        $fullProfile = [
            'score_fg'               => $scoreFg,
            'section_bac'            => $sectionBac,
            'filiere_etudiant_actuelle' => $sectionBac,
            'vecteur_psychometrique' => $vecteurRiasec,
            'gatb_scores'            => $gatbScores,
            'code_holland'           => $codeHolland,
            // SIAEPI v4.0 : Dimensions composites
            'big_five' => [
                // v5.0 : clés B5_ en priorité, fallback ancienne notation
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
            ]
        ];

        $result = $engine->recommend($fullProfile, (int) $request->input('top_n', 12));

        // ── 5. Remap au format attendu par la vue ────────────────────────
        if (!isset($result['error']) && !empty($result['recommandations'])) {
            $recommendations = [
                'recommandations'  => $result['recommandations'],
                'diagnostic'       => $result['diagnostic'],
                'gap_analysis'     => $result['gap_analysis'] ?? [],
                'resume'           => $result['diagnostic']['diagnostic'] ?? '',
                'total_filieres_accessibles' => $result['total_scorees'] ?? 0,
            ];
        } else {
            $recommendations = ['error' => $result['error'] ?? 'Erreur du moteur de recommandation.'];
        }

        return view('recommendations.show', [
            'recommendations' => $recommendations,
            'profilRiasec'    => $profilRiasec,
            'codeHolland'     => $codeHolland,
            'scoreFg'         => $scoreFg,
            'gapAnalysis'     => $result['gap_analysis'] ?? [],
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────
    // Construit un texte de mots-clés psychométriques à partir des réponses
    // aux blocs Big Five, GATB et Schwartz (réponses ≥ 4 = forte adhésion)
    // ──────────────────────────────────────────────────────────────────────
    private function buildTextoFromAnswers(?string $sessionId, string $codeHolland, string $section): string
    {
        // Mots-clés liés au code Holland dominant
        $hollandKeywords = [
            'R' => 'technique mécanique construction pratique outils',
            'I' => 'analyse logique données recherche scientifique',
            'A' => 'créativité expression artistique design innovation',
            'S' => 'aide accompagnement enseignement social communication',
            'E' => 'leadership négociation gestion projet ambition',
            'C' => 'organisation procédures rigueur données administration',
        ];

        $keywords = [];

        // Ajoute mots-clés RIASEC des 3 premières lettres du code Holland
        foreach (str_split($codeHolland) as $lettre) {
            if (isset($hollandKeywords[$lettre])) {
                $keywords[] = $hollandKeywords[$lettre];
            }
        }

        // Ajoute mots-clés académiques
        $sectionKeywords = [
            'Informatique' => 'informatique programmation algorithme réseau système logiciel data',
            'Mathématiques'=> 'mathématiques calcul abstraction modélisation probabilité',
            'Économie'     => 'économie finance marché gestion comptabilité commerce',
            'Sciences'     => 'sciences biologie chimie physique laboratoire expérience',
            'Lettres'      => 'lettres langues littérature traduction',
        ];
        $keywords[] = $sectionKeywords[$section] ?? 'autonomie résolution problèmes organisation';

        // Si une session existe, ajoute des mots-clés depuis les réponses fortes (≥4)
        if ($sessionId) {
            $strongAnswers = AnswerRiasec::where('test_session_id', $sessionId)
                ->where('valeur', '>=', 4)
                ->with('question')
                ->get();

            $dimKeyMap = [
                // Big Five
                'O'   => 'ouverture curiosité créativité imagination',
                'C'   => 'organisation rigueur méthode discipline',
                'E'   => 'leadership communication énergie sociabilité',
                'A'   => 'coopération bienveillance empathie harmonie',
                'N'   => 'stabilité calme résilience gestion stress',
                // GATB
                'G'   => 'intelligence générale raisonnement apprentissage',
                'V'   => 'verbal communication lecture rédaction',
                'Num' => 'numérique calcul statistiques données',
                'Sp'  => 'spatial visualisation plan schéma conception',
                // Schwartz
                'Sec' => 'sécurité stabilité protection prévoyance',
                'Ach' => 'réussite performance ambition excellence',
                'Ben' => 'bienveillance aide communauté solidarité',
                'Aut' => 'autonomie indépendance liberté initiative',
            ];

            foreach ($strongAnswers as $answer) {
                $dim = $answer->question?->dimension;
                if ($dim && isset($dimKeyMap[$dim])) {
                    $keywords[] = $dimKeyMap[$dim];
                }
            }
        }

        return implode(' ', array_unique($keywords));
    }
}

