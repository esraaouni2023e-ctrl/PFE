<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRiasecAnswerRequest;
use App\Models\ProfileRiasec;
use App\Models\QuestionRiasec;
use App\Services\RIASEC\TestManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * RiasecTestController — Contrôleur du test Holland RIASEC.
 *
 * Cycle complet :
 *   start()        → initialise la session + redirige vers la 1re question
 *   showQuestion() → affiche la question courante (avec progression)
 *   storeAnswer()  → enregistre la réponse (AJAX) et retourne la suivante
 *   complete()     → finalise le test, sauvegarde le profil, redirige résultats
 *   results()      → affiche les résultats du profil RIASEC
 *
 * Compatibilité : utilisateurs authentifiés ET invités (session PHP).
 */
class RiasecTestController extends Controller
{
    public function __construct(private readonly TestManager $testManager) {}

    // ══════════════════════════════════════════════════════════════════════
    // 1. DÉMARRAGE DU TEST
    // ══════════════════════════════════════════════════════════════════════

    /**
     * GET /riasec/demarrer
     *
     * Initialise une nouvelle session de test RIASEC.
     * Si un test est déjà en cours, propose de continuer ou recommencer.
     */
    public function start(Request $request): View|RedirectResponse
    {
        $existingSession = session('riasec_session_id');

        // Si un test est déjà en cours, on propose de continuer
        if ($existingSession) {
            $progress = $this->testManager->getProgress(
                Auth::id(),
                $existingSession
            );

            if (! $progress->isCompleted && $progress->answered > 0) {
                return view('riasec.start', [
                    'hasOngoingTest' => true,
                    'progress'       => $progress,
                    'totalQuestions' => $progress->total,
                ]);
            }
        }

        return view('riasec.start', [
            'hasOngoingTest' => false,
            'totalQuestions' => QuestionRiasec::actives()->count(),
        ]);
    }

    /**
     * POST /riasec/demarrer
     *
     * Crée une nouvelle session et redirige vers la première question.
     * Le paramètre `restart` force la réinitialisation.
     */
    public function initialize(Request $request): RedirectResponse
    {
        $forceRestart = $request->boolean('restart', false);

        $existingSession = session('riasec_session_id');

        // On ne recrée la session que si demandé ou inexistante
        if ($forceRestart || ! $existingSession) {
            $sessionId = $this->testManager->generateSessionId();
            session([
                'riasec_session_id'    => $sessionId,
                'riasec_started_at'    => now()->toIso8601String(),
                'riasec_current_step'  => 1,
            ]);
        }

        return redirect()->route('riasec.question', ['step' => 1])
            ->with('info', 'Votre test RIASEC a démarré. Répondez honnêtement à chaque question.');
    }

    // ══════════════════════════════════════════════════════════════════════
    // 2. AFFICHAGE D'UNE QUESTION
    // ══════════════════════════════════════════════════════════════════════

    /**
     * GET /riasec/question/{step}
     *
     * Affiche la question à l'étape $step.
     * Redirige vers results() si le test est terminé.
     */
    public function showQuestion(Request $request, int $step = 1): View|RedirectResponse
    {
        $sessionId = session('riasec_session_id');
        $userId    = Auth::id();

        // Test terminé → résultats
        if ($this->testManager->isTestCompleted($userId, $sessionId)) {
            return redirect()->route('riasec.results')
                ->with('success', 'Vous avez déjà complété ce test. Voici vos résultats.');
        }

        // Toutes les questions dans l'ordre
        $allQuestions = $this->testManager->getAllQuestions();
        $totalSteps   = $allQuestions->count();

        // Validation de l'étape
        $step = max(1, min($step, $totalSteps));

        /** @var QuestionRiasec|null $question */
        $question = $allQuestions->values()->get($step - 1);

        if (! $question) {
            return redirect()->route('riasec.results');
        }

        // Progression courante
        $progress = $this->testManager->getProgress($userId, $sessionId);

        // Réponse existante pour cette question (pour pré-sélectionner)
        $existingAnswer = \App\Models\AnswerRiasec::session($sessionId)
            ->where('question_id', $question->id)
            ->value('valeur');

        // Mise à jour de l'étape courante en session
        session(['riasec_current_step' => $step]);

        return view('riasec.question', [
            'question'       => $question,
            'step'           => $step,
            'totalSteps'     => $totalSteps,
            'progress'       => $progress,
            'existingAnswer' => $existingAnswer,
            'labels'         => QuestionRiasec::LIKERT_LABELS,
            'isLast'         => $step === $totalSteps,
            'sessionId'      => $sessionId,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════
    // 3. ENREGISTREMENT D'UNE RÉPONSE (AJAX)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * POST /riasec/repondre  [AJAX]
     *
     * Enregistre la réponse, calcule la progression et retourne la prochaine question.
     * Retourne du JSON pour la navigation dynamique côté client.
     */
    public function storeAnswer(StoreRiasecAnswerRequest $request): JsonResponse
    {
        $sessionId  = $request->riasecSessionId();
        $userId     = Auth::id();
        $guestId    = Auth::check() ? null : session()->getId();

        try {
            // Enregistrement de la réponse
            $this->testManager->saveAnswer(
                userId:     $userId,
                questionId: $request->integer('question_id'),
                score:      $request->integer('valeur'),
                sessionId:  $sessionId,
                guestId:    $guestId,
                tempsMs:    $request->integer('temps_ms') ?: null,
            );

            $progress = $this->testManager->getProgress($userId, $sessionId);

            // Test terminé ?
            if ($progress->isCompleted) {
                return response()->json([
                    'success'    => true,
                    'completed'  => true,
                    'progress'   => $progress->toArray(),
                    'redirect'   => route('riasec.complete'),
                    'message'    => 'Test terminé ! Calcul de votre profil en cours…',
                ]);
            }

            // Calcule l'étape suivante
            $currentStep = session('riasec_current_step', 1);
            $nextStep    = $currentStep + 1;

            session(['riasec_current_step' => $nextStep]);

            return response()->json([
                'success'    => true,
                'completed'  => false,
                'next_step'  => $nextStep,
                'next_url'   => route('riasec.question', ['step' => $nextStep]),
                'progress'   => $progress->toArray(),
                'message'    => "Réponse enregistrée ({$progress->answered}/{$progress->total}).",
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer.',
            ], 500);
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    // 4. FINALISATION DU TEST
    // ══════════════════════════════════════════════════════════════════════

    /**
     * GET /riasec/terminer
     *
     * Calcule les scores définitifs, sauvegarde le ProfileRiasec,
     * nettoie la session et redirige vers les résultats.
     */
    public function complete(Request $request): RedirectResponse
    {
        $sessionId = session('riasec_session_id');
        $userId    = Auth::id();
        $guestId   = Auth::check() ? null : session()->getId();

        // Vérification que le test est bien complété
        if (! $this->testManager->isTestCompleted($userId, $sessionId)) {
            $progress = $this->testManager->getProgress($userId, $sessionId);
            $step     = $progress->answered + 1;

            return redirect()
                ->route('riasec.question', ['step' => $step])
                ->with('warning', 'Vous n\'avez pas encore répondu à toutes les questions.');
        }

        // Sauvegarde du profil en base
        $profil = $this->testManager->saveProfile($userId, $sessionId, $guestId);

        // Stocke l'ID du profil en session pour la page résultats
        session(['riasec_profile_id' => $profil->id]);

        // Nettoyage partiel de session (on garde le profile_id)
        session()->forget(['riasec_current_step', 'riasec_started_at']);
        $this->testManager->invalidateSessionCache($sessionId);

        return redirect()
            ->route('riasec.results')
            ->with('success', 'Votre profil RIASEC a été calculé avec succès !');
    }

    // ══════════════════════════════════════════════════════════════════════
    // 5. AFFICHAGE DES RÉSULTATS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * GET /riasec/resultats
     *
     * Affiche la page de résultats du profil RIASEC.
     * Cherche le profil via session, ou le dernier profil complété de l'utilisateur.
     */
    public function results(Request $request): View|RedirectResponse
    {
        $profileId = session('riasec_profile_id');
        $userId    = Auth::id();

        // Récupération du profil
        $profil = null;

        if ($profileId) {
            $profil = ProfileRiasec::find($profileId);
        }

        // Fallback : dernier profil complété de l'utilisateur authentifié
        if (! $profil && $userId) {
            $profil = ProfileRiasec::pourUser($userId)->complets()->recents()->first();
        }

        if (! $profil) {
            return redirect()
                ->route('riasec.start')
                ->with('warning', 'Aucun résultat disponible. Veuillez passer le test.');
        }

        // Calcul des scores si pas encore fait (sécurité)
        $sessionId = session('riasec_session_id');
        $scores    = null;
        if ($sessionId) {
            try {
                $scores = $this->testManager->calculateScores($userId, $sessionId);
            } catch (\Throwable) {
                // Silencieux : on utilise les données du profil en base
            }
        }

        // Profil des 3 dimensions dominantes pour l'affichage
        $trigramDims = str_split($profil->code_holland);
        $dimProfiles = array_map(
            fn ($dim) => $this->testManager->getDimensionProfile($dim),
            $trigramDims
        );

        return view('riasec.results', [
            'profil'      => $profil,
            'scores'      => $scores,
            'dimProfiles' => $dimProfiles,
            'trigram'     => $profil->code_holland,
            'interp'      => $profil->interpretation ?? [],
            'scoresSorted'=> $profil->scores_par_dimension,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════
    // 6. ACTIONS UTILITAIRES
    // ══════════════════════════════════════════════════════════════════════

    /**
     * DELETE /riasec/reinitialiser
     *
     * Réinitialise la session de test (pour recommencer depuis le début).
     */
    public function reset(Request $request): RedirectResponse
    {
        session()->forget([
            'riasec_session_id',
            'riasec_started_at',
            'riasec_current_step',
            'riasec_profile_id',
        ]);

        return redirect()
            ->route('riasec.start')
            ->with('info', 'Votre session de test a été réinitialisée.');
    }

    /**
     * GET /riasec/progression  [AJAX]
     *
     * Retourne la progression courante au format JSON (pour polling).
     */
    public function progressJson(Request $request): JsonResponse
    {
        $sessionId = session('riasec_session_id');

        if (! $sessionId) {
            return response()->json(['error' => 'Aucun test en cours.'], 404);
        }

        $progress = $this->testManager->getProgress(Auth::id(), $sessionId);

        return response()->json($progress->toArray());
    }
}
