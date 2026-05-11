<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRiasecAnswerRequest;
use App\Models\AnswerRiasec;
use App\Models\Profile;
use App\Models\ProfileRiasec;
use App\Models\QuestionRiasec;
use App\Models\RiasecTestSession;
use App\Services\RIASEC\RecommendationService;
use App\Services\RIASEC\AdaptiveTestEngine;
use App\Services\RIASEC\TestManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RiasecTestController extends Controller
{
    public function __construct(
        private readonly AdaptiveTestEngine $engine,
        private readonly TestManager $testManager,
        private readonly RecommendationService $recommendationService,
    ) {}

    public function start(Request $request): View|RedirectResponse
    {
        $sessionId = session('riasec_session_id');
        $adaptiveSession = $sessionId
            ? RiasecTestSession::where('session_token', $sessionId)->first()
            : null;

        if ($adaptiveSession && ! $adaptiveSession->isTerminated()) {
            $progress = $this->testManager->getProgress(Auth::id(), $sessionId);

            if ($progress->answered === 0) {
                return redirect()->route('riasec.question', ['step' => 1]);
            }

            if ($progress->answered > 0) {
                return view('riasec.start', [
                    'hasOngoingTest' => true,
                    'progress' => $progress,
                    'totalQuestions' => min($adaptiveSession->max_questions, QuestionRiasec::actives()->count()),
                ]);
            }
        }

        return view('riasec.start', [
            'hasOngoingTest' => false,
            'totalQuestions' => min(AdaptiveTestEngine::MAX_QUESTIONS, QuestionRiasec::actives()->count()),
        ]);
    }

    public function initialize(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'age' => ['nullable', 'integer', 'min:12', 'max:80'],
            'niveau_etudes' => ['nullable', 'string', 'max:80'],
            'filieres_envisagees' => ['nullable', 'string', 'max:1000'],
            'matieres_aimees' => ['nullable', 'string', 'max:1000'],
            'matieres_detestees' => ['nullable', 'string', 'max:1000'],
        ]);

        $forceRestart = $request->boolean('restart', false);
        $existingSession = session('riasec_session_id');
        $existingAdaptiveSession = $existingSession
            ? RiasecTestSession::where('session_token', $existingSession)->first()
            : null;

        if ($forceRestart || ! $existingAdaptiveSession || $existingAdaptiveSession->isTerminated()) {
            $result = $this->engine->startTest(
                Auth::id(),
                array_filter($validated, fn ($value) => filled($value))
            );

            /** @var RiasecTestSession $session */
            $session = $result['session'];

            session([
                'riasec_session_id' => $session->session_token,
                'riasec_session_db_id' => $session->id,
                'riasec_started_at' => now()->toIso8601String(),
                'riasec_current_step' => 1,
            ]);
        }

        return redirect()->route('riasec.question', ['step' => 1])
            ->with('info', 'Votre test RIASEC a demarre. Repondez honnetement a chaque question.');
    }

    public function showQuestion(Request $request, int $step = 1): View|RedirectResponse
    {
        $sessionId = session('riasec_session_id');

        if (! $sessionId) {
            return redirect()->route('riasec.question.entry')
                ->with('warning', 'Aucun test en cours. Veuillez d abord demarrer le test.');
        }

        $userId = Auth::id();
        $adaptiveSession = RiasecTestSession::where('session_token', $sessionId)->first();

        if ($adaptiveSession?->isTerminated()) {
            if (! session('riasec_profile_id')) {
                return redirect()->route('riasec.complete');
            }

            return redirect()->route('riasec.results')
                ->with('success', 'Vous avez deja complete ce test. Voici vos resultats.');
        }

        $question = $adaptiveSession
            ? $this->engine->getNextQuestion($sessionId)
            : $this->testManager->getNextQuestion($userId, $sessionId);

        if (! $question) {
            return redirect()->route('riasec.complete');
        }

        $progress = $this->testManager->getProgress($userId, $sessionId);
        $step = max(1, $progress->answered + 1);
        $totalSteps = $adaptiveSession
            ? min($adaptiveSession->max_questions, QuestionRiasec::actives()->count())
            : QuestionRiasec::actives()->count();

        $existingAnswer = AnswerRiasec::session($sessionId)
            ->where('question_id', $question->id)
            ->value('valeur');

        session(['riasec_current_step' => $step]);
        $isAdaptive = (bool) $adaptiveSession;

        // Feedback anti-ennui : Messages d'encouragement aux passages de vagues
        $feedback = null;
        if ($step == 19) {
            $feedback = "✨ Super ! La première phase de découverte est terminée. Continuons pour affiner ton profil.";
        } elseif ($step == 31) {
            $feedback = "🎯 Excellent ! Ton profil se dessine de plus en plus clairement. Encore un petit effort !";
        } elseif ($step == 43) {
            $feedback = "🚀 On y est presque ! Merci pour ta persévérance, tes réponses sont très précieuses.";
        }

        return view('riasec.question', [
            'question' => $question,
            'step' => $step,
            'totalSteps' => $totalSteps,
            'progress' => $progress,
            'existingAnswer' => $existingAnswer,
            'labels' => QuestionRiasec::LIKERT_LABELS,
            'isLast' => $step >= $totalSteps,
            'sessionId' => $sessionId,
            'isAdaptive' => $isAdaptive,
            'feedback' => $feedback,
        ]);
    }

    public function storeAnswer(StoreRiasecAnswerRequest $request): JsonResponse
    {
        $sessionId = $request->riasecSessionId();
        $userId = Auth::id();
        $guestId = Auth::check() ? null : session()->getId();

        try {
            $adaptiveSession = RiasecTestSession::where('session_token', $sessionId)->first();
            $result = null;

            if ($adaptiveSession) {
                $result = $this->engine->submitAnswer(
                    sessionToken: $sessionId,
                    questionId: $request->integer('question_id'),
                    score: $request->integer('valeur'),
                    tempsMs: $request->integer('temps_ms') ?: null,
                );
            } else {
                $this->testManager->saveAnswer(
                    userId: $userId,
                    questionId: $request->integer('question_id'),
                    score: $request->integer('valeur'),
                    sessionId: $sessionId,
                    guestId: $guestId,
                    tempsMs: $request->integer('temps_ms') ?: null,
                );
            }

            $progress = $this->testManager->getProgress($userId, $sessionId);
            $terminate = $result['terminate'] ?? null;

            if (($terminate['should_stop'] ?? false) || $progress->isCompleted) {
                return response()->json([
                    'success' => true,
                    'completed' => true,
                    'progress' => $progress->toArray(),
                    'precision' => isset($terminate['precision']) ? round($terminate['precision']) : null,
                    'reason' => $terminate['reason'] ?? null,
                    'redirect' => route('riasec.complete'),
                    'message' => 'Test termine. Calcul de votre profil en cours...',
                ]);
            }

            $nextStep = $progress->answered + 1;
            session(['riasec_current_step' => $nextStep]);

            return response()->json([
                'success' => true,
                'completed' => false,
                'next_step' => $nextStep,
                'next_url' => route('riasec.question', ['step' => $nextStep]),
                'progress' => $progress->toArray(),
                'current_code' => $result['session']->code_holland_provis ?? null,
                'message' => "Reponse enregistree ({$progress->answered}/{$progress->total}).",
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
                'message' => 'Une erreur est survenue. Veuillez reessayer.',
            ], 500);
        }
    }

    public function complete(Request $request): RedirectResponse
    {
        $sessionId = session('riasec_session_id');

        if (! $sessionId) {
            return redirect()->route('riasec.question.entry')
                ->with('warning', 'Aucun test en cours. Veuillez passer le test.');
        }

        $userId = Auth::id();
        $guestId = Auth::check() ? null : session()->getId();
        $adaptiveSession = RiasecTestSession::where('session_token', $sessionId)->first();

        // Si la session est déjà marquée comme complète en base
        if ($adaptiveSession && $adaptiveSession->isTerminated()) {
            if (!session('riasec_profile_id')) {
                $profil = ProfileRiasec::where('test_session_id', $sessionId)->first();
                if ($profil) session(['riasec_profile_id' => $profil->id]);
            }
            return redirect()->route('riasec.results');
        }

        $terminate = $adaptiveSession ? $this->engine->shouldTerminateTest($adaptiveSession) : null;
        $isCompleted = ($terminate['should_stop'] ?? false) || $this->testManager->isTestCompleted($userId, $sessionId);

        if (! $isCompleted) {
            // Vérifier s'il reste au moins une question disponible
            $nextQuestion = $adaptiveSession
                ? $this->engine->getNextQuestion($sessionId)
                : $this->testManager->getNextQuestion($userId, $sessionId);

            if ($nextQuestion) {
                $progress = $this->testManager->getProgress($userId, $sessionId);
                return redirect()
                    ->route('riasec.question', ['step' => max(1, $progress->answered + 1)])
                    ->with('warning', 'Vous n avez pas encore repondu a assez de questions.');
            }
            // S'il n'y a plus de questions, on force la complétion du test.
        }

        $profil = $adaptiveSession
            ? $this->engine->generateFinalProfile($sessionId)
            : $this->testManager->saveProfile($userId, $sessionId, $guestId);

        session(['riasec_profile_id' => $profil->id]);
        $this->storeRecommendationsInSession($userId, $profil);

        session()->forget(['riasec_current_step', 'riasec_started_at']);
        $this->testManager->invalidateSessionCache($sessionId);

        return redirect()
            ->route('riasec.results')
            ->with('success', 'Votre profil RIASEC a ete calcule avec succes.');
    }

    public function results(Request $request): View|RedirectResponse
    {
        $profileId = session('riasec_profile_id');
        $userId = Auth::id();
        $profil = $profileId ? ProfileRiasec::find($profileId) : null;

        if (! $profil && $userId) {
            $profil = ProfileRiasec::pourUser($userId)->complets()->recents()->first();
        }

        if (! $profil) {
            return redirect()
                ->route('riasec.question.entry')
                ->with('warning', 'Aucun resultat disponible. Veuillez passer le test.');
        }

        $sessionId = session('riasec_session_id');
        $scores = null;

        if ($sessionId) {
            try {
                $scores = $this->testManager->calculateScores($userId, $sessionId);
            } catch (\Throwable) {
                $scores = null;
            }
        }

        $dimProfiles = array_map(
            fn ($dim) => $this->testManager->getDimensionProfile($dim),
            str_split($profil->code_holland)
        );

        $recommendationsData = session('riasec_recommendations');

        return view('riasec.results', [
            'profil' => $profil,
            'scores' => $scores,
            'dimProfiles' => $dimProfiles,
            'trigram' => $profil->code_holland,
            'interp' => $profil->interpretation ?? [],
            'scoresSorted' => $profil->scores_par_dimension,
            'recommendations' => $recommendationsData['recommendations'] ?? [],
            'totalFilieres' => $recommendationsData['total_filieres_accessibles'] ?? null,
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        session()->forget([
            'riasec_session_id',
            'riasec_session_db_id',
            'riasec_started_at',
            'riasec_current_step',
            'riasec_profile_id',
            'riasec_recommendations',
        ]);

        return redirect()
            ->route('riasec.question.entry')
            ->with('info', 'Votre session de test a ete reinitialisee.');
    }

    public function autoRun(Request $request): RedirectResponse
    {
        $userId = Auth::id();
        $guestId = Auth::check() ? null : session()->getId();
        $sessionId = $this->testManager->generateSessionId();

        session([
            'riasec_session_id' => $sessionId,
            'riasec_started_at' => now()->toIso8601String(),
            'riasec_current_step' => 1,
        ]);

        $questions = $this->testManager->getAllQuestions();

        if ($questions->isEmpty()) {
            return redirect()->route('riasec.question.entry')
                ->with('error', 'Aucune question disponible. Verifiez que les donnees sont bien importees.');
        }

        $defaultValues = [3, 4, 3, 2, 4, 3, 5, 3];

        foreach ($questions as $i => $question) {
            $valeur = max(1, min(5, $defaultValues[$i % count($defaultValues)]));

            try {
                $this->testManager->saveAnswer(
                    userId: $userId,
                    questionId: $question->id,
                    score: $valeur,
                    sessionId: $sessionId,
                    guestId: $guestId,
                );
            } catch (\Throwable) {
                // La simulation express continue meme si un item isole echoue.
            }
        }

        $profil = $this->testManager->saveProfile($userId, $sessionId, $guestId);
        session(['riasec_profile_id' => $profil->id]);
        $this->storeRecommendationsInSession($userId, $profil);

        session()->forget(['riasec_current_step', 'riasec_started_at']);
        $this->testManager->invalidateSessionCache($sessionId);

        return redirect()->route('riasec.results')
            ->with('success', 'Simulation express terminee. Voici votre profil RIASEC et vos recommandations.');
    }

    public function progressJson(Request $request): JsonResponse
    {
        $sessionId = session('riasec_session_id');

        if (! $sessionId) {
            return response()->json(['error' => 'Aucun test en cours.'], 404);
        }

        return response()->json(
            $this->testManager->getProgress(Auth::id(), $sessionId)->toArray()
        );
    }

    private function storeRecommendationsInSession(?int $userId, ProfileRiasec $profil): void
    {
        if (! $userId) {
            return;
        }

        $academicProfile = Profile::where('user_id', $userId)->first();
        $scoreFg = $academicProfile?->score_fg;
        $codeHolland = $profil->code_holland;

        if (! $codeHolland) {
            return;
        }

        $recommendations = $this->recommendationService->getTopRecommendations(
            $codeHolland,
            $scoreFg ? (float) $scoreFg : null
        );

        if (!empty($recommendations)) {
            session(['riasec_recommendations' => [
                'recommendations' => $recommendations,
                'total_filieres_accessibles' => \App\Models\Filiere::count(),
            ]]);
            return;
        }

        Log::info('RiasecTestController recommendations unavailable', [
            'user_id' => $userId,
            'reason' => $result['error'] ?? 'aucune recommandation retournee',
        ]);
    }
}
