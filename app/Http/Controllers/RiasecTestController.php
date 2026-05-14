<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRiasecAnswerRequest;
use App\Models\AnswerRiasec;
use App\Models\Profile;
use App\Models\ProfileRiasec;
use App\Models\QuestionRiasec;
use App\Services\RIASEC\TestManager;
use App\Services\RIASEC\EarlyStoppingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RiasecTestController extends Controller
{
    public function __construct(
        private readonly TestManager $testManager,
        private readonly EarlyStoppingService $earlyStoppingService,
        private readonly \App\Services\RIASEC\AdaptiveTestEngine $adaptiveTestEngine,
        private readonly \App\Services\RIASEC\PostTestValidator $postTestValidator,
    ) {}

    public function start(Request $request): View|RedirectResponse
    {
        $sessionId = session('riasec_session_id');

        if ($sessionId) {
            $progress = $this->testManager->getProgress(Auth::id(), $sessionId);

            if ($progress->answered === 0) {
                return redirect()->route('riasec.question', ['step' => 1]);
            }

            if ($progress->answered > 0 && !$progress->isCompleted) {
                return view('riasec.start', [
                    'hasOngoingTest' => true,
                    'progress' => $progress,
                    'totalQuestions' => QuestionRiasec::actives()->count(),
                ]);
            }
        }

        return view('riasec.start', [
            'hasOngoingTest' => false,
            'totalQuestions' => QuestionRiasec::actives()->count(),
        ]);
    }

    public function initialize(Request $request): RedirectResponse
    {
        $forceRestart = $request->boolean('restart', false);
        $existingSession = session('riasec_session_id');

        if ($forceRestart || ! $existingSession) {
            $sessionId = Str::uuid()->toString();

            session([
                'riasec_session_id' => $sessionId,
                'riasec_started_at' => now()->toIso8601String(),
                'riasec_current_step' => 1,
            ]);
        }

        return redirect()->route('riasec.question', ['step' => 1])
            ->with('info', 'Votre test a demarre. Repondez honnetement a chaque question.');
    }

    public function showQuestion(Request $request, int $step = 1): View|RedirectResponse
    {
        $sessionId = session('riasec_session_id');

        if (! $sessionId) {
            return redirect()->route('riasec.question.entry')
                ->with('warning', 'Aucun test en cours. Veuillez d abord demarrer le test.');
        }

        $userId = Auth::id();
        $catState = $this->adaptiveTestEngine->getSessionState($sessionId);

        if ($catState['is_completed'] || $this->testManager->isTestCompleted($userId, $sessionId)) {
            if (! session('riasec_profile_id')) {
                return redirect()->route('riasec.complete');
            }

            return redirect()->route('riasec.results')
                ->with('success', 'Vous avez deja complete ce test. Voici vos resultats.');
        }

        $question = $this->adaptiveTestEngine->getNextQuestion($sessionId, $userId);

        if (! $question) {
            return redirect()->route('riasec.complete');
        }

        $progress = $this->testManager->getProgress($userId, $sessionId);
        $step = max(1, $progress->answered + 1);
        $totalSteps = QuestionRiasec::actives()->count();

        $existingAnswer = AnswerRiasec::session($sessionId)
            ->where('question_id', $question->id)
            ->value('valeur');

        session(['riasec_current_step' => $step]);

        // Feedback en cours de route
        $feedback = null;
        $earlyStopData = null;
        
        if ($step == 19) {
            $feedback = "✨ Super ! La première phase de découverte est terminée. Continuons pour affiner ton profil.";
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
            'feedback' => $feedback,
            'earlyStopData' => $earlyStopData,
        ]);
    }

    public function storeAnswer(StoreRiasecAnswerRequest $request): JsonResponse
    {
        $sessionId = $request->riasecSessionId();
        $userId = Auth::id();
        $guestId = Auth::check() ? null : session()->getId();

        try {
            $tempsMs = $request->integer('temps_ms') ?: 5000;
            
            $answer = $this->testManager->saveAnswer(
                userId: $userId,
                questionId: $request->integer('question_id'),
                score: $request->integer('valeur'),
                sessionId: $sessionId,
                guestId: $guestId,
                tempsMs: $tempsMs,
            );

            // Traitement par le moteur adaptatif bayésien
            $catState = $this->adaptiveTestEngine->processAnswer($sessionId, $answer, $tempsMs);
            $progress = $this->testManager->getProgress($userId, $sessionId);

            $stop = $catState['is_completed'];
            
            // Calcul de la confiance moyenne (top 3) pour affichage
            $certainties = collect($catState['dimensions'])->pluck('certainty')->sortDesc()->take(3);
            $confidence = $certainties->average();

            if ($stop || $progress->isCompleted) {
                
                if ($stop) {
                    session(['riasec_stopped_early' => true]);
                    session(['riasec_confidence_score' => $confidence]);
                    session(['riasec_blocks_completed' => ceil($progress->answered / 6)]);
                }

                return response()->json([
                    'success' => true,
                    'completed' => true,
                    'progress' => $progress->toArray(),
                    'redirect' => route('riasec.complete'),
                    'message' => 'Test terminé. Calcul de votre profil en cours...',
                    'early_stop' => $stop,
                    'confidence' => $confidence,
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
                'message' => "Réponse enregistrée. Phase: " . $catState['phase'],
                'confidence' => $confidence,
                'show_gauge' => true,
                'show_fraud_warning' => $catState['show_fraud_warning'] ?? false,
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

        $isCompleted = $this->testManager->isTestCompleted($userId, $sessionId);
        $stoppedEarly = session('riasec_stopped_early', false);

        if (! $isCompleted && ! $stoppedEarly) {
            $nextQuestion = $this->testManager->getNextQuestion($userId, $sessionId);

            if ($nextQuestion) {
                $progress = $this->testManager->getProgress($userId, $sessionId);
                return redirect()
                    ->route('riasec.question', ['step' => max(1, $progress->answered + 1)])
                    ->with('warning', 'Vous n\'avez pas encore répondu à assez de questions.');
            }
        }

        if (!session('riasec_profile_id')) {
            $profil = $this->testManager->saveProfile($userId, $sessionId, $guestId);
            
            if (session('riasec_stopped_early')) {
                $profil->update([
                    'stopped_early' => true,
                    'confidence_score' => session('riasec_confidence_score'),
                    'blocks_completed' => session('riasec_blocks_completed'),
                ]);
            }

            // Phase 4 : Validation Post-Test (Comportementale)
            $catState = $this->adaptiveTestEngine->getSessionState($sessionId);
            $this->postTestValidator->validateProfile($profil, $catState);
            
            session(['riasec_profile_id' => $profil->id]);
        }

        session()->forget([
            'riasec_current_step', 
            'riasec_started_at', 
            'riasec_stopped_early', 
            'riasec_confidence_score', 
            'riasec_blocks_completed'
        ]);
        $this->testManager->invalidateSessionCache($sessionId);

        return redirect()
            ->route('riasec.results')
            ->with('success', 'Votre profil a ete calcule avec succes. Cliquez sur CapAvenir IA pour vos recommandations.');
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

        return view('riasec.results', [
            'profil' => $profil,
            'scores' => $scores,
            'dimProfiles' => $dimProfiles,
            'trigram' => $profil->code_holland,
            'interp' => $profil->interpretation ?? [],
            'scoresSorted' => $profil->scores_par_dimension,
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        session()->forget([
            'riasec_session_id',
            'riasec_started_at',
            'riasec_current_step',
            'riasec_profile_id',
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
            }
        }

        $profil = $this->testManager->saveProfile($userId, $sessionId, $guestId);
        session(['riasec_profile_id' => $profil->id]);

        session()->forget(['riasec_current_step', 'riasec_started_at']);
        $this->testManager->invalidateSessionCache($sessionId);

        return redirect()->route('riasec.results')
            ->with('success', 'Simulation express terminee. Voici votre profil psychometrique complet.');
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
}
