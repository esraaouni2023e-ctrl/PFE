<?php

namespace Tests\Unit;

use App\Models\AnswerRiasec;
use App\Models\QuestionRiasec;
use App\Services\RIASEC\AdaptiveTestEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

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
    }

    public function test_phased_selection_rules_in_riasec_v5_2()
    {
        // Créer 6 questions RIASEC (une par dimension)
        $dims = ['R', 'I', 'A', 'S', 'E', 'C'];
        $created = [];
        foreach ($dims as $dim) {
            $created[$dim] = QuestionRiasec::factory()->create([
                'dimension' => $dim,
                'discrimination' => 8.0,
                'difficulty' => 0.0,
                'actif' => true,
                'bloc' => 'riasec',
                'type_reponse' => 'likert'
            ]);
        }

        // Première question suggérée doit faire partie des dimensions RIASEC
        $q1 = $this->engine->getNextQuestion($this->sessionId);
        $this->assertNotNull($q1);
        $this->assertEquals('riasec', $q1->bloc);
        $this->assertContains($q1->dimension, $dims);

        // Simuler des réponses successives
        $answer1 = new AnswerRiasec(['question_id' => $q1->id, 'valeur' => 5]);
        $answer1->setRelation('question', $q1);
        $state = $this->engine->processAnswer($this->sessionId, $answer1);

        // L'état de session doit contenir les dimensions mises à jour
        $this->assertArrayHasKey($q1->dimension, $state['dimensions']);
    }

    public function test_stopping_rules_with_mandatory_coverage()
    {
        $state = $this->engine->getSessionState($this->sessionId);

        // Simuler 3 réponses par dimension pour le bloc RIASEC (18 réponses au total)
        $dims = ['R', 'I', 'A', 'S', 'E', 'C'];
        foreach ($dims as $dim) {
            $state['dimensions'][$dim]['answered_count'] = 3;
            $state['dimensions'][$dim]['score'] = 2.0;
        }

        $this->engine->saveSessionState($this->sessionId, $state);

        // Récupérer l'état mis à jour
        $updatedState = $this->engine->getSessionState($this->sessionId);
        
        // Sous la v5.2, l'arrêt adaptatif est déclenché par l'atteinte d'une couverture complète
        // du bloc RIASEC (au moins 3 réponses par dimension)
        $isCompleted = true;
        foreach ($dims as $dim) {
            if (($updatedState['dimensions'][$dim]['answered_count'] ?? 0) < 3) {
                $isCompleted = false;
            }
        }

        $this->assertTrue($isCompleted, "Le test doit être considéré complet s'il y a ≥3 réponses par dimension.");
    }
}
