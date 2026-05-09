/**
 * MODIFICATIONS DU RiasecTestController POUR LE MOTEUR ADAPTATIF
 * ================================================================
 * Remplacer TestManager par AdaptiveTestEngine dans le constructeur.
 *
 * AVANT :
 *   public function __construct(private readonly TestManager $testManager) {}
 *
 * APRÈS :
 *   public function __construct(
 *       private readonly AdaptiveTestEngine $engine,
 *       private readonly TestManager $testManager,   // gardé pour generateInterpretation
 *   ) {}
 *
 *
 * MÉTHODE initialize() — POST /riasec/demarrer
 * ─────────────────────────────────────────────
 * REMPLACER :
 *   $sessionId = $this->testManager->generateSessionId();
 *   session(['riasec_session_id' => $sessionId, ...]);
 *
 * PAR :
 *   $demographic = $request->only(['age', 'bac_type', 'region']);
 *   $result      = $this->engine->startTest(Auth::id(), $demographic);
 *   $session     = $result['session'];
 *
 *   session([
 *       'riasec_session_id'    => $session->session_token,
 *       'riasec_session_db_id' => $session->id,
 *       'riasec_started_at'    => now()->toIso8601String(),
 *   ]);
 *
 *   return redirect()->route('riasec.question', ['step' => 1]);
 *
 *
 * MÉTHODE showQuestion() — GET /riasec/question/{step}
 * ──────────────────────────────────────────────────────
 * REMPLACER la récupération de question par :
 *   $question = $this->engine->getNextQuestion($sessionId);
 *   if (! $question) {
 *       return redirect()->route('riasec.complete');
 *   }
 *   $progress = ... (inchangé, via TestManager::getProgress)
 *
 *
 * MÉTHODE storeAnswer() — POST /riasec/repondre [AJAX]
 * ─────────────────────────────────────────────────────
 * REMPLACER le bloc try/catch par :
 *   $result = $this->engine->submitAnswer(
 *       sessionToken: $sessionId,
 *       questionId:   $request->integer('question_id'),
 *       score:        $request->integer('valeur'),
 *       tempsMs:      $request->integer('temps_ms') ?: null,
 *   );
 *
 *   $terminate = $result['terminate'];
 *
 *   if ($terminate['should_stop']) {
 *       return response()->json([
 *           'success'   => true,
 *           'completed' => true,
 *           'precision' => round($terminate['precision']),
 *           'reason'    => $terminate['reason'],
 *           'redirect'  => route('riasec.complete'),
 *       ]);
 *   }
 *
 *   $nextQ    = $result['next_question'];
 *   $progress = $this->testManager->getProgress(Auth::id(), $sessionId);
 *
 *   return response()->json([
 *       'success'      => true,
 *       'completed'    => false,
 *       'next_url'     => route('riasec.question', ['step' => $progress->answered + 1]),
 *       'progress'     => $progress->toArray(),
 *       'next_dim'     => $nextQ?->dimension,   // utile pour l'UI (badge couleur)
 *       'current_code' => $result['session']->code_holland_provis,
 *   ]);
 *
 *
 * MÉTHODE complete() — GET /riasec/terminer
 * ─────────────────────────────────────────
 * REMPLACER $this->testManager->saveProfile() par :
 *   $profil = $this->engine->generateFinalProfile($sessionId);
 *
 */
