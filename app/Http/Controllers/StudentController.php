<?php

namespace App\Http\Controllers;

use App\Models\AnswerRiasec;
use App\Models\Profile;
use App\Models\ProfileRiasec;
use App\Models\QuestionRiasec;
use App\Services\AdmissionPredictorService;
use App\Services\RecommendationService;
use App\Services\RIASEC\GatbCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected AdmissionPredictorService $predictor;
    protected RecommendationService $recommendationService;

    public function __construct(AdmissionPredictorService $predictor, RecommendationService $recommendationService)
    {
        $this->predictor = $predictor;
        $this->recommendationService = $recommendationService;
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

            // Extraction des scores réels GATB
            $sessionId = $profilRiasec->test_session_id;
            if ($sessionId) {
                $rawGatbAnswers = \App\Models\AnswerRiasec::where('test_session_id', $sessionId)
                    ->whereHas('question', function($q) {
                        $q->whereIn('dimension', ['G', 'V', 'Num', 'Sp']);
                    })
                    ->with('question')
                    ->get()
                    ->map(function($ans) {
                        $dimMap = ['Num' => 'N', 'Sp' => 'S'];
                        $dim = $ans->question->dimension;
                        return [
                            'dimension' => $dimMap[$dim] ?? $dim,
                            'score'     => $ans->valeur
                        ];
                    })->toArray();

                if (!empty($rawGatbAnswers)) {
                    $gatbCalc = new \App\Services\RIASEC\GatbCalculator();
                    $gScores  = $gatbCalc->calculateScores($rawGatbAnswers);

                    $dynamicSkills[] = [
                        'label' => 'Logique GATB (G)',
                        'val'   => round(($gScores['G'] ?? 10) * 5), // sur 100
                        'color' => 'var(--accent2)'
                    ];
                    $dynamicSkills[] = [
                        'label' => 'Calcul GATB (N)',
                        'val'   => round(($gScores['N'] ?? 10) * 5),
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

            $gatbScores = ['G' => 10, 'V' => 10, 'N' => 10, 'S' => 10, 'TOTAL' => 10];
            if ($sessionId) {
                $rawGatbAnswers = \App\Models\AnswerRiasec::where('test_session_id', $sessionId)
                    ->whereHas('question', function($q) {
                        $q->whereIn('dimension', ['G', 'V', 'Num', 'Sp']);
                    })
                    ->with('question')
                    ->get()
                    ->map(function($ans) {
                        $dimMap = ['Num' => 'N', 'Sp' => 'S'];
                        $dim = $ans->question->dimension;
                        return [
                            'dimension' => $dimMap[$dim] ?? $dim,
                            'score'     => $ans->valeur
                        ];
                    })->toArray();

                if (!empty($rawGatbAnswers)) {
                    $gatbCalc   = new \App\Services\RIASEC\GatbCalculator();
                    $gatbScores = $gatbCalc->calculateScores($rawGatbAnswers);
                }
            }

            $profilEtudiant = [
                'score_fg'                 => (float) $scoreFg,
                'filiere_etudiant_actuelle'=> $sectionBac,
                'texte_psycho'             => $textoKeywords,
                'vecteur_psychometrique'   => $vecteurRiasec,
                'gatb_scores'              => $gatbScores,
            ];

            // On demande le Top 6 à l'IA
            try {
                $recs = $this->recommendationService->getRecommendations($profilEtudiant, null, 6);
                if (!isset($recs['error']) && !empty($recs['recommandations'])) {
                    $predictions = [];
                    foreach ($recs['recommandations'] as $r) {
                        $predictions[] = [
                            'icon'  => '🎯',
                            'name'  => $r['Nom_Filiere'] ?? 'Formation',
                            'univ'  => ($r['Etablissement'] ?? '') . ' – ' . ($r['Universite'] ?? ''),
                            'score' => isset($r['Score_Final_Contextuel']) ? round($r['Score_Final_Contextuel'] * 100) : 80,
                            'code'  => $r['Code_Filiere'] ?? ''
                        ];
                    }
                }
            } catch (\Exception $e) {
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
        $scoreFg         = $academicProfile?->score_fg ?? 120;  // fallback 120
        $sectionBac      = $academicProfile?->section_bac ?? 'Informatique';

        // ── 2. Dernier profil RIASEC complété ────────────────────────────
        $profilRiasec = ProfileRiasec::pourUser($userId)
            ->complets()
            ->recents()
            ->first();

        // Vecteur psychométrique RIASEC normalisé (0.0 – 1.0)
        $vecteurRiasec = ['R'=>0.5,'I'=>0.5,'A'=>0.5,'S'=>0.5,'E'=>0.5,'C'=>0.5];
        $codeHolland   = 'ISA';

        if ($profilRiasec) {
            $maxScore = 100;
            $vecteurRiasec = [
                'R' => round($profilRiasec->score_r / $maxScore, 4),
                'I' => round($profilRiasec->score_i / $maxScore, 4),
                'A' => round($profilRiasec->score_a / $maxScore, 4),
                'S' => round($profilRiasec->score_s / $maxScore, 4),
                'E' => round($profilRiasec->score_e / $maxScore, 4),
                'C' => round($profilRiasec->score_c / $maxScore, 4),
            ];
            $codeHolland = $profilRiasec->code_holland;
        }

        // ── 3. Réponses multi-blocs (Big Five, GATB, Schwartz) ──────────
        $sessionId    = session('riasec_session_id') ?? $profilRiasec?->test_session_id;
        $textoKeywords = $this->buildTextoFromAnswers($sessionId, $codeHolland, $sectionBac);
        
        // ── 3b. Calcul des scores GATB réels de l'étudiant ──────────────
        $gatbScores = ['G' => 10, 'V' => 10, 'N' => 10, 'S' => 10, 'TOTAL' => 10]; // Defaults
        if ($sessionId) {
            $rawGatbAnswers = AnswerRiasec::where('test_session_id', $sessionId)
                ->whereHas('question', function($q) {
                    $q->whereIn('dimension', ['G', 'V', 'Num', 'Sp']);
                })
                ->with('question')
                ->get()
                ->map(function($ans) {
                    $dimMap = ['Num' => 'N', 'Sp' => 'S']; // Mapping bdd -> gatb
                    $dim = $ans->question->dimension;
                    return [
                        'dimension' => $dimMap[$dim] ?? $dim,
                        'score' => $ans->valeur
                    ];
                })->toArray();
                
            if (!empty($rawGatbAnswers)) {
                $gatbCalculator = new GatbCalculator();
                $gatbScores = $gatbCalculator->calculateScores($rawGatbAnswers);
            }
        }

        // ── 4. Construction du profil_etudiant complet ───────────────────
        $profilEtudiant = [
            'score_fg'                 => (float) $scoreFg,
            'filiere_etudiant_actuelle'=> $sectionBac,
            'texte_psycho'             => $textoKeywords,
            'vecteur_psychometrique'   => $vecteurRiasec,
            'gatb_scores'              => $gatbScores,
        ];

        // ── 5. Appel à l'API Python ──────────────────────────────────────
        $recommendations = $this->recommendationService->getRecommendations(
            $profilEtudiant,
            null,
            $request->input('top_n', 12)
        );

        if (isset($recommendations['error'])) {
            return view('recommendations.error', ['message' => $recommendations['error']]);
        }

        return view('recommendations.show', [
            'recommendations' => $recommendations,
            'profilRiasec'    => $profilRiasec,
            'codeHolland'     => $codeHolland,
            'scoreFg'         => $scoreFg,
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
            'Informatique' => 'programmation algorithme réseau système logiciel',
            'Mathématiques'=> 'calcul abstraction modélisation probabilité',
            'Économie'     => 'finance marché économie gestion comptabilité',
            'Sciences'     => 'biologie chimie physique laboratoire expérience',
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

