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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        $profileTimeline = [];
        $recommendationsGenerated = false;

        $toPercent = function ($value): ?int {
            if ($value === null || $value === '') {
                return null;
            }

            $percent = (float) $value;
            if ($percent > 0 && $percent <= 1) {
                $percent *= 100;
            }

            return (int) round(max(0, min(100, $percent)));
        };

        $completedRiasecCount = ProfileRiasec::pourUser($user->id)->complets()->count();
        $hasAcademicProfile = (bool) ($profile?->score_fg || $profile?->is_academique_complet);

        $profilIaScore = 0;
        $dashboardStats = [
            'tests_completed' => $completedRiasecCount + ($hasAcademicProfile ? 1 : 0),
            'suggested_paths' => 0,
            'reliability_score' => 0,
        ];

        if ($profilRiasec) {
            $qualityScores = array_filter([
                $toPercent($profilRiasec->confidence_score),
                $toPercent($profilRiasec->score_coherence),
            ], fn ($score) => $score !== null);

            $profilIaScore = !empty($qualityScores)
                ? (int) round(array_sum($qualityScores) / count($qualityScores))
                : 0;
            $dashboardStats['reliability_score'] = $profilIaScore;

            $profileTimeline[] = [
                'title' => 'Test RIASEC adaptatif',
                'date' => optional($profilRiasec->complete_at ?? $profilRiasec->created_at)->format('d/m/Y') ?? 'Date non disponible',
                'score' => $profilIaScore . '%',
            ];

            $gatbTimelineScore = collect([
                $profilRiasec->score_gatb_g,
                $profilRiasec->score_gatb_v,
                $profilRiasec->score_gatb_n,
                $profilRiasec->score_gatb_s,
            ])->filter(fn ($score) => $score !== null && $score > 0);

            if ($gatbTimelineScore->isNotEmpty()) {
                $profileTimeline[] = [
                    'title' => 'Aptitudes cognitives GATB',
                    'date' => optional($profilRiasec->complete_at ?? $profilRiasec->updated_at)->format('d/m/Y') ?? 'Date non disponible',
                    'score' => round($gatbTimelineScore->avg()) . '%',
                ];
            }

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
            $resolvedGatb = $this->resolveGatbScores($sessionId, $profilRiasec);
            $gScoreG = $resolvedGatb['GATB_G'] ?? 0;
            $gScoreN = $resolvedGatb['GATB_N'] ?? 0;

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
        } else {
            $academicProgress = $profile ? ($toPercent($profile->progression) ?? 0) : 0;
            $scoreFgProgress = $profile?->score_fg ? (int) round(min(100, ($profile->score_fg / 200) * 100)) : 0;
            $moyenneProgress = $profile?->moyenne_generale ? (int) round(min(100, ($profile->moyenne_generale / 20) * 100)) : 0;

            $dynamicSkills = [
                ['label'=>'Profil académique', 'val'=>$academicProgress, 'color'=>'var(--accent)'],
                ['label'=>'Score FG normalisé', 'val'=>$scoreFgProgress, 'color'=>'var(--accent2)'],
                ['label'=>'Moyenne BAC', 'val'=>$moyenneProgress, 'color'=>'var(--gold)'],
            ];
        }

        if ($profile?->score_fg) {
            $profileTimeline[] = [
                'title' => 'Score FG académique',
                'date' => optional($profile->score_fg_updated_at ?? $profile->updated_at)->format('d/m/Y') ?? 'Date non disponible',
                'score' => round($profile->score_fg, 1),
            ];
        }

        if (empty($profileTimeline)) {
            $profileTimeline[] = [
                'title' => 'Profil à compléter',
                'date' => 'En attente',
                'score' => '0%',
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
            $gatbScores = $this->resolveGatbScores($sessionId, $profilRiasec);
            $gatbScores['TOTAL'] = round(
                (($gatbScores['GATB_G'] ?? 0) + ($gatbScores['GATB_V'] ?? 0) +
                 ($gatbScores['GATB_N'] ?? 0) + ($gatbScores['GATB_S'] ?? 0)) / 4, 1
            );

            $adaptiveEngine = new \App\Services\RIASEC\AdaptiveTestEngine();
            $catState = $adaptiveEngine->getSessionState($sessionId);

            $profilEtudiant = [
                'id'                       => $user->id,
                'sem'                      => $profilRiasec ? (1.0 - ((float)$profilRiasec->confidence_score > 1.0 ? (float)$profilRiasec->confidence_score / 100.0 : (float)$profilRiasec->confidence_score)) : 0.30,
                'score_fg'                 => (float) $scoreFg,
                'section_bac'              => $sectionBac,
                'filiere_etudiant_actuelle'=> $sectionBac,
                'texte_psycho'             => $textoKeywords,
                'vecteur_psychometrique'   => $vecteurRiasec,
                'gatb_scores'              => $gatbScores,
                'code_holland'             => $codeHolland,
                'notes_matieres'           => $profile?->notes_matieres ?? [],
                'interests' => [
                    'MED'      => $catState['dimensions']['MED']['score'] ?? 0.0,
                    'ENG'      => $catState['dimensions']['ENG']['score'] ?? 0.0,
                    'INFO'     => $catState['dimensions']['INFO']['score'] ?? 0.0,
                    'DROIT'    => $catState['dimensions']['DROIT']['score'] ?? 0.0,
                    'ECO'      => $catState['dimensions']['ECO']['score'] ?? 0.0,
                    'EDU'      => $catState['dimensions']['EDU']['score'] ?? 0.0,
                    'ART'      => $catState['dimensions']['ART']['score'] ?? 0.0,
                    'LTR'      => $catState['dimensions']['LTR']['score'] ?? 0.0,
                    'SOC'      => $catState['dimensions']['SOC']['score'] ?? 0.0,
                    'SPO'      => $catState['dimensions']['SPO']['score'] ?? 0.0,
                    'ARCHI'    => $catState['dimensions']['ARCHI']['score'] ?? 0.0,
                    'declared' => $profile?->interests ?? '',
                ]
            ];

            // On demande le Top 6 au moteur SIAEPI PHP-natif
            try {
                $engine = new \App\Services\SiaepiRecommendationEngine();
                $recs = $engine->recommend($profilEtudiant, 6);
                
                if (!isset($recs['error']) && !empty($recs['recommandations'])) {
                    $recommendationsGenerated = true;
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

        $dashboardStats['suggested_paths'] = $recommendationsGenerated ? count($predictions) : 0;
        $profilRingOffset = round(540.35 - (540.35 * ($profilIaScore / 100)), 2);

        return view('student.dashboard', compact(
            'studentName',
            'profile',
            'portfolios',
            'roadmaps',
            'predictions',
            'profilRiasec',
            'dynamicSkills',
            'profilIaScore',
            'profilRingOffset',
            'dashboardStats',
            'profileTimeline'
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

        // ── Guard : les deux étapes doivent être complétées ──────────────
        if (!$scoreFg || !$profilRiasec) {
            $message = !$scoreFg
                ? 'Veuillez d\'abord compléter votre profil académique et calculer votre score FG.'
                : 'Veuillez d\'abord passer le test psychométrique RIASEC.';

            return redirect()
                ->route('student.pipeline')
                ->with('warning', $message);
        }

        $vecteurRiasec = ['R'=>0.5,'I'=>0.5,'A'=>0.5,'S'=>0.5,'E'=>0.5,'C'=>0.5];
        $codeHolland   = 'ISA';

        if ($profilRiasec) {
            // Normalise 0–1 : division par 100 (échelle max des scores RIASEC)
            // ⚠ Même normalisation que dans index() pour garantir la cohérence des vecteurs
            $maxScore = 100;
            $vecteurRiasec = [
                'R' => round((float)$profilRiasec->score_r / $maxScore, 4),
                'I' => round((float)$profilRiasec->score_i / $maxScore, 4),
                'A' => round((float)$profilRiasec->score_a / $maxScore, 4),
                'S' => round((float)$profilRiasec->score_s / $maxScore, 4),
                'E' => round((float)$profilRiasec->score_e / $maxScore, 4),
                'C' => round((float)$profilRiasec->score_c / $maxScore, 4),
            ];
            $codeHolland = $profilRiasec->code_holland;
        }

        // ── 3. Scores GATB réels (v5.0) ─────────────────────────────────
        $sessionId  = session('riasec_session_id') ?? $profilRiasec?->test_session_id;

        // Priorité aux colonnes pré-calculées du profil
        $gatbScores = $this->resolveGatbScores($sessionId, $profilRiasec);

        // ── 4. Appel au moteur SIAEPI PHP-natif ─────────────────────────
        $engine = new SiaepiRecommendationEngine();
        $adaptiveEngine = new \App\Services\RIASEC\AdaptiveTestEngine();
        
        // Récupération des dimensions complètes depuis l'état adaptatif
        $catState = $adaptiveEngine->getSessionState($sessionId);
        
        $fullProfile = [
            'id'                     => $userId,
            'sem'                    => $profilRiasec ? (1.0 - ((float)$profilRiasec->confidence_score > 1.0 ? (float)$profilRiasec->confidence_score / 100.0 : (float)$profilRiasec->confidence_score)) : 0.30,
            'score_fg'               => $scoreFg,
            'section_bac'            => $sectionBac,
            'filiere_etudiant_actuelle' => $sectionBac,
            'vecteur_psychometrique' => $vecteurRiasec,
            'gatb_scores'            => $gatbScores,
            'code_holland'           => $codeHolland,
            'notes_matieres'         => $academicProfile?->notes_matieres ?? [],
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
                'MED'      => $catState['dimensions']['MED']['score'] ?? 0.0,
                'ENG'      => $catState['dimensions']['ENG']['score'] ?? 0.0,
                'INFO'     => $catState['dimensions']['INFO']['score'] ?? 0.0,
                'DROIT'    => $catState['dimensions']['DROIT']['score'] ?? 0.0,
                'ECO'      => $catState['dimensions']['ECO']['score'] ?? 0.0,
                'EDU'      => $catState['dimensions']['EDU']['score'] ?? 0.0,
                'ART'      => $catState['dimensions']['ART']['score'] ?? 0.0,
                'LTR'      => $catState['dimensions']['LTR']['score'] ?? 0.0,
                'SOC'      => $catState['dimensions']['SOC']['score'] ?? 0.0,
                'SPO'      => $catState['dimensions']['SPO']['score'] ?? 0.0,
                'ARCHI'    => $catState['dimensions']['ARCHI']['score'] ?? 0.0,
                'declared' => $academicProfile?->interests ?? '',
            ]
        ];

        $result = $engine->recommend($fullProfile, (int) $request->input('top_n', 8));

        if (!isset($result['error']) && !empty($result['recommandations'])) {
            // Sauvegarde dans la table recommendations
            try {
                \App\Models\Recommendation::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'source'  => 'SIAEPI_v5',
                    ],
                    [
                        'title'       => 'Recommandations SIAEPI ' . $codeHolland,
                        'description' => 'Recommandations automatiques basées sur votre profil ' . $codeHolland . ' (Score FG : ' . $scoreFg . ')',
                        'data'        => $result,
                        'relevance'   => 1.0,
                    ]
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("SIAEPI: Erreur sauvegarde recommandations: " . $e->getMessage());
            }
        } else {
            // Recouvrement depuis le cache BDD si erreur (Excel absent ou vide)
            $cached = \App\Models\Recommendation::where('user_id', $userId)->where('source', 'SIAEPI_v5')->first();
            if ($cached && !empty($cached->data)) {
                $result = $cached->data;
            }
        }

        // ── 5. Remap au format attendu par la vue ────────────────────────
        if (!isset($result['error']) && (!empty($result['recommandations']) || !empty($result['ambitieuses']))) {
            $recommendations = [
                'recommandations'  => $result['recommandations'],
                'accessibles'      => $result['accessibles'] ?? [],
                'securite'         => $result['securite'] ?? [],
                'ambitieuses'      => $result['ambitieuses'] ?? [],
                'diagnostic'       => $result['diagnostic'],
                'gap_analysis'     => $result['gap_analysis'] ?? [],
                'resume'           => $result['diagnostic']['diagnostic'] ?? '',
                'total_filieres_accessibles' => $result['total_scorees'] ?? 0,
            ];
        } else {
            $recommendations = ['error' => $result['error'] ?? 'Erreur du moteur de recommandation. Les fichiers Excel de données sont indisponibles et aucun historique n\'est enregistré.'];
        }

        $feedbacks = \App\Models\RecommendationFeedback::where('user_id', $userId)
            ->get()
            ->keyBy('filiere_code')
            ->toArray();

        return view('recommendations.show', [
            'recommendations' => $recommendations,
            'profilRiasec'    => $profilRiasec,
            'codeHolland'     => $codeHolland,
            'scoreFg'         => $scoreFg,
            'gapAnalysis'     => $result['gap_analysis'] ?? [],
            'feedbacks'       => $feedbacks,
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

    /**
     * Store recommendation feedback from AJAX request.
     */
    public function storeFeedback(Request $request)
    {
        $validated = $request->validate([
            'filiere_code' => 'required|string|max:50|exists:filieres,code_filiere',
            'rating' => 'required|integer|min:1|max:5',
            'is_relevant' => 'required|boolean',
            'comment' => 'nullable|string|max:1000',
        ]);

        try {
            $feedback = \App\Models\RecommendationFeedback::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'filiere_code' => $validated['filiere_code'],
                ],
                [
                    'rating' => $validated['rating'],
                    'is_relevant' => $validated['is_relevant'],
                    'comment' => $validated['comment'] ?? null,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Feedback enregistré avec succès !',
                'feedback' => $feedback
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur enregistrement feedback pour user " . auth()->id() . ": " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Impossible d\'enregistrer le feedback.'
            ], 500);
        }
    }

    /**
     * Enregistre une interaction d'un étudiant avec une filière (clic, sauvegarde, etc.).
     */
    public function storeInteraction(Request $request)
    {
        $validated = $request->validate([
            'filiere_code' => 'required|string|max:50|exists:filieres,code_filiere',
            'action' => 'required|string|in:view,save,ignore',
        ]);

        try {
            $userId = auth()->id();
            
            // Calcul du poids
            $weight = match($validated['action']) {
                'save' => 0.08,
                'view' => 0.03,
                'ignore' => -0.10,
                default => 0.0,
            };

            $interaction = \App\Models\StudentInteraction::create([
                'user_id' => $userId,
                'filiere_code' => $validated['filiere_code'],
                'action' => $validated['action'],
                'weight' => $weight,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Interaction enregistrée avec succès !',
                'interaction' => $interaction
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur enregistrement interaction pour user " . auth()->id() . ": " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Impossible d\'enregistrer l\'interaction.'
            ], 500);
        }
    }

    /**
     * Resolve and recalculate GATB scores if missing from profile.
     */
    private function resolveGatbScores(?string $sessionId, ?ProfileRiasec $profilRiasec): array
    {
        $gatbScores = [
            'GATB_G' => $profilRiasec?->score_gatb_g ?? 0,
            'GATB_V' => $profilRiasec?->score_gatb_v ?? 0,
            'GATB_N' => $profilRiasec?->score_gatb_n ?? 0,
            'GATB_S' => $profilRiasec?->score_gatb_s ?? 0,
        ];

        $sum = (float)($gatbScores['GATB_G'] ?? 0) + 
               (float)($gatbScores['GATB_V'] ?? 0) + 
               (float)($gatbScores['GATB_N'] ?? 0) + 
               (float)($gatbScores['GATB_S'] ?? 0);

        if ($sum === 0.0 && $sessionId) {
            $rawGatbAnswers = AnswerRiasec::where('test_session_id', $sessionId)
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
                $gatbCalc = new GatbCalculator();
                $calculated = $gatbCalc->calculateScores($rawGatbAnswers);
                $gatbScores = [
                    'GATB_G' => $calculated['GATB_G'] ?? 0,
                    'GATB_V' => $calculated['GATB_V'] ?? 0,
                    'GATB_N' => $calculated['GATB_N'] ?? 0,
                    'GATB_S' => $calculated['GATB_S'] ?? 0,
                ];
            }
        }

        return $gatbScores;
    }
}
