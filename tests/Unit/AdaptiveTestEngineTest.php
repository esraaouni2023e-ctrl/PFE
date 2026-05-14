<?php

namespace Tests\Unit;

use App\Models\AnswerRiasec;
use App\Models\QuestionRiasec;
use App\Services\RIASEC\AdaptiveTestEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Mockery;

class AdaptiveTestEngineTest extends TestCase
{
    use RefreshDatabase;

    private AdaptiveTestEngine $engine;
    private string $sessionId = 'test_session_123';

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new AdaptiveTestEngine();
        Cache::flush();

        // Config par défaut pour les tests
        Config::set('adaptive_test.max_questions', 20);
        Config::set('adaptive_test.stopping_rules.min_certainty_threshold', 80.0);
        Config::set('adaptive_test.stopping_rules.min_dimensions_reached', 3);
        Config::set('adaptive_test.learning_rate.base', 0.5);
        Config::set('adaptive_test.learning_rate.decay_floor', 0.2);
    }

    public function test_phase_0_selects_most_discriminating_questions()
    {
        // Créer quelques questions avec différentes discriminations
        QuestionRiasec::factory()->create(['dimension' => 'R', 'discrimination' => 5.0, 'actif' => true]);
        $qHigh1 = QuestionRiasec::factory()->create(['dimension' => 'I', 'discrimination' => 9.5, 'actif' => true]);
        $qHigh2 = QuestionRiasec::factory()->create(['dimension' => 'S', 'discrimination' => 9.0, 'actif' => true]);

        // Première question doit être celle à 9.5
        $q1 = $this->engine->getNextQuestion($this->sessionId);
        $this->assertEquals($qHigh1->id, $q1->id);

        // Simuler une réponse
        $answer1 = new AnswerRiasec(['question_id' => $q1->id, 'valeur' => 5]);
        $answer1->setRelation('question', $q1);
        $this->engine->processAnswer($this->sessionId, $answer1);

        // Deuxième question doit être celle à 9.0
        $q2 = $this->engine->getNextQuestion($this->sessionId);
        $this->assertEquals($qHigh2->id, $q2->id);

        // Simuler une deuxième réponse
        $answer2 = new AnswerRiasec(['question_id' => $q2->id, 'valeur' => 5]);
        $answer2->setRelation('question', $q2);
        $state = $this->engine->processAnswer($this->sessionId, $answer2);

        // Phase doit maintenant être 1
        $this->assertEquals(1, $state['phase']);
    }

    public function test_learning_rate_decays_over_time()
    {
        $q = QuestionRiasec::factory()->create(['dimension' => 'R', 'discrimination' => 10.0, 'actif' => true, 'type_reponse' => 'likert']);
        
        $answer = new AnswerRiasec(['question_id' => $q->id, 'valeur' => 5]);
        $answer->setRelation('question', $q);

        // Réponse 1 (decay faible)
        $state1 = $this->engine->processAnswer($this->sessionId, $answer);
        $scoreAfter1 = $state1['dimensions']['R']['score']; // Devrait être élevé car 5 (cible = +3.0)
        
        // Simuler qu'on a déjà répondu à 18 questions (numAnswered sera 19)
        $state = $this->engine->getSessionState($this->sessionId);
        $state['answered_ids'] = array_fill(0, 18, 999); 
        // Reset score
        $state['dimensions']['R']['score'] = 0.0;
        $this->engine->saveSessionState($this->sessionId, $state);

        // Réponse "identique" mais avec decay max
        $state2 = $this->engine->processAnswer($this->sessionId, $answer);
        $scoreAfterLate = $state2['dimensions']['R']['score'];

        // L'impact de la première question doit être supérieur à l'impact de la question tardive
        $this->assertTrue($scoreAfter1 > $scoreAfterLate, "Le decay n'a pas réduit l'impact de l'apprentissage.");
    }

    public function test_early_stopping_when_3_dimensions_reach_80_percent_certainty()
    {
        $state = $this->engine->getSessionState($this->sessionId);
        
        // Simuler 3 dimensions proches du seuil
        $state['dimensions']['R']['certainty'] = 75.0;
        $state['dimensions']['I']['certainty'] = 75.0;
        $state['dimensions']['A']['certainty'] = 75.0;
        $this->engine->saveSessionState($this->sessionId, $state);

        // Question pour R
        $qR = QuestionRiasec::factory()->create(['dimension' => 'R', 'discrimination' => 10.0, 'actif' => true]);
        $answerR = new AnswerRiasec(['question_id' => $qR->id, 'valeur' => 4]);
        $answerR->setRelation('question', $qR);
        $this->engine->processAnswer($this->sessionId, $answerR); // R passe à >80%

        // Question pour I
        $qI = QuestionRiasec::factory()->create(['dimension' => 'I', 'discrimination' => 10.0, 'actif' => true]);
        $answerI = new AnswerRiasec(['question_id' => $qI->id, 'valeur' => 4]);
        $answerI->setRelation('question', $qI);
        $this->engine->processAnswer($this->sessionId, $answerI); // I passe à >80%

        // Question pour A (déclenchera l'arrêt)
        $qA = QuestionRiasec::factory()->create(['dimension' => 'A', 'discrimination' => 10.0, 'actif' => true]);
        $answerA = new AnswerRiasec(['question_id' => $qA->id, 'valeur' => 4]);
        $answerA->setRelation('question', $qA);
        
        $finalState = $this->engine->processAnswer($this->sessionId, $answerA); // A passe à >80% -> ARRÊT

        $this->assertTrue($finalState['is_completed']);
        $this->assertEquals("certitude_atteinte", $finalState['completed_reason']);
    }
}
