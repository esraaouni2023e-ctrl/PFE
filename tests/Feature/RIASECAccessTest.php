<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ProfileRiasec;
use App\Models\QuestionRiasec;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RIASECAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_completed_riasec_profile_is_expired_on_restart()
    {
        // 1. Create student
        $student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
            'is_admin' => false,
        ]);

        // 2. Create a completed profile for the student
        $profile = ProfileRiasec::create([
            'user_id' => $student->id,
            'test_session_id' => 'old-session-uuid',
            'score_r' => 50,
            'score_i' => 60,
            'score_a' => 70,
            'score_s' => 80,
            'score_e' => 90,
            'score_c' => 40,
            'code_holland' => 'ESA',
            'statut' => ProfileRiasec::STATUT_COMPLET,
            'nb_questions_repondues' => 30,
            'nb_questions_total' => 30,
            'complete_at' => now(),
        ]);

        $this->assertEquals(ProfileRiasec::STATUT_COMPLET, $profile->fresh()->statut);

        // 3. Request test initialization with restart parameter
        $response = $this->actingAs($student)->post(route('riasec.initialize'), [
            'restart' => '1',
        ]);

        // 4. Assert deactivation/expiration
        $this->assertEquals(ProfileRiasec::STATUT_EXPIRE, $profile->fresh()->statut);
        $this->assertStringStartsWith(route('riasec.question', ['step' => 1]), $response->headers->get('Location'));
    }

    public function test_store_answer_accepts_boolean_values()
    {
        // 1. Create student
        $student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
            'is_admin' => false,
        ]);

        // 2. Create boolean question
        $question = QuestionRiasec::factory()->create([
            'type_reponse' => 'boolean',
            'actif' => true,
        ]);

        // 3. Post answer 0 (Non)
        $response = $this->actingAs($student)
            ->withSession(['riasec_session_id' => 'test-session-uuid'])
            ->post(route('riasec.answer'), [
                'question_id' => $question->id,
                'valeur' => 0,
                'temps_ms' => 1000,
            ]);

        // 4. Assert response success
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // 5. Assert database has answer
        $this->assertDatabaseHas('riasec_answers', [
            'question_id' => $question->id,
            'valeur' => 0,
            'test_session_id' => 'test-session-uuid',
        ]);
    }
}
