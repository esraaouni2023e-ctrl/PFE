<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\ProfileRiasec;
use App\Services\RecommendationService;
use App\Services\RIASEC\TestManager;
use App\Services\ScoreFGService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * OrientationPipelineController
 *
 * Gère le flux complet d'orientation en 3 étapes successives via UN seul bouton :
 *
 *   Étape 1 — Calcul du score FG
 *             → Si le profil académique est incomplet, redirige vers la page profil
 *             → Si le score est déjà calculé, passe à l'étape suivante
 *
 *   Étape 2 — Test RIASEC
 *             → Si aucun profil RIASEC complété, redirige vers le test
 *             → Si le test est déjà complété, passe à l'étape suivante
 *
 *   Étape 3 — Analyse & Recommandations
 *             → Appelle l'API Python avec score_fg + code_holland
 *             → Redirige vers la page résultats RIASEC avec les recommandations
 */
class OrientationPipelineController extends Controller
{
    public function __construct(
        private readonly TestManager           $testManager,
        private readonly RecommendationService $recommendationService,
        private readonly ScoreFGService        $scoreFgService,
    ) {}

    /**
     * GET /student/pipeline
     *
     * Point d'entrée unique du pipeline.
     * Détecte l'étape courante et redirige vers la bonne action.
     */
    public function start(Request $request): RedirectResponse|View
    {
        $user    = Auth::user();
        $userId  = $user->id;
        $profile = Profile::where('user_id', $userId)->first();

        // ── Étape 1 : Vérifier le score FG ─────────────────────────────────
        if (!$profile || !$profile->score_fg) {
            $sections = $this->scoreFgService->getSections();
            $gouvernorats = [
                'Ariana', 'Béja', 'Ben Arous', 'Bizerte', 'Gabès',
                'Gafsa', 'Jendouba', 'Kairouan', 'Kasserine', 'Kébili',
                'Le Kef', 'Mahdia', 'La Manouba', 'Médenine', 'Monastir',
                'Nabeul', 'Sfax', 'Sidi Bouzid', 'Siliana', 'Sousse',
                'Tataouine', 'Tozeur', 'Tunis', 'Zaghouan',
            ];

            return view('student.pipeline.step1_score', [
                'profile'      => $profile ?? new Profile(),
                'sections'     => $sections,
                'gouvernorats' => $gouvernorats,
            ]);
        }

        // ── Étape 2 : Vérifier si le test RIASEC est complété ──────────────
        $riasecProfil = ProfileRiasec::pourUser($userId)
            ->complets()
            ->recents()
            ->first();

        if (!$riasecProfil) {
            return redirect()
                ->route('riasec.question.entry')
                ->with('info', '🧠 Étape 2/3 — Passez le test RIASEC pour identifier votre profil psychologique.');
        }

        // ── Étape 3 : Générer les recommandations via l'API Python ─────────
        $scoreFg     = $profile->score_fg;
        $codeHolland = $riasecProfil->code_holland;

        $result = $this->recommendationService->getRecommendations(
            (float) $scoreFg,
            $codeHolland
        );

        if (!$result['success']) {
            return redirect()
                ->route('riasec.results')
                ->with('warning', '⚠️ Étape 3/3 — Votre profil RIASEC est prêt mais le service de recommandations est inaccessible. Vérifiez que l\'API Python est démarrée.');
        }

        if (empty($result['data']['recommendations'])) {
            return redirect()
                ->route('riasec.results')
                ->with('info', 'ℹ️ Aucune filière trouvée pour ce profil. Essayez de repasser le test RIASEC.');
        }

        // Stocke les recommandations en session et redirige vers résultats
        session([
            'riasec_profile_id'      => $riasecProfil->id,
            'riasec_recommendations' => $result['data'],
        ]);

        Log::info('OrientationPipeline – recommandations générées', [
            'user_id'      => $userId,
            'score_fg'     => $scoreFg,
            'code_holland' => $codeHolland,
            'count'        => count($result['data']['recommendations']),
        ]);

        return redirect()
            ->route('riasec.results')
            ->with('success', '✅ Étape 3/3 — Analyse complète ! Voici vos recommandations personnalisées.');
    }

    /**
     * POST /student/pipeline/step1
     *
     * Enregistre les notes, calcule le score FG, puis démarre le test RIASEC.
     */
    public function storeStep1(Request $request): RedirectResponse
    {
        $request->validate([
            'section_bac'      => 'required|string',
            'moyenne_generale' => 'required|numeric|min:0|max:20',
            'annee_bac'        => 'required|integer',
            'gouvernorat'      => 'required|string',
            'notes_matieres'   => 'required|array',
        ]);

        $user    = Auth::user();
        $profile = Profile::firstOrCreate(['user_id' => $user->id]);

        try {
            $scoreFg = $this->scoreFgService->calculer(
                $request->input('section_bac'),
                (float) $request->input('moyenne_generale'),
                $request->input('notes_matieres')
            );

            $profile->update([
                'section_bac'         => $request->input('section_bac'),
                'moyenne_generale'    => $request->input('moyenne_generale'),
                'annee_bac'           => $request->input('annee_bac'),
                'gouvernorat'         => $request->input('gouvernorat'),
                'notes_matieres'      => $request->input('notes_matieres'),
                'score_fg'            => $scoreFg,
                'score_fg_updated_at' => now(),
            ]);

            // Démarre automatiquement le test RIASEC
            return redirect()->route('riasec.question.entry')
                             ->with('info', '✅ Score calculé avec succès. Voici les questions du test psychologique.');

        } catch (\Exception $e) {
            return back()->withErrors(['erreur' => 'Erreur lors du calcul du score : ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Retourne le statut du pipeline pour l'étudiant connecté.
     * Utilisé pour afficher les indicateurs d'étapes dans le bouton.
     */
    public static function getStatus(int $userId): array
    {
        $profile      = Profile::where('user_id', $userId)->first();
        $hasScore     = $profile && $profile->score_fg;
        $riasecProfil = ProfileRiasec::pourUser($userId)->complets()->recents()->first();
        $hasRiasec    = (bool) $riasecProfil;

        $step = 1;
        if ($hasScore) $step = 2;
        if ($hasScore && $hasRiasec) $step = 3;

        return [
            'step'       => $step,
            'has_score'  => $hasScore,
            'has_riasec' => $hasRiasec,
            'score_fg'   => $profile?->score_fg,
            'code'       => $riasecProfil?->code_holland,
        ];
    }
}
