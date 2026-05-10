<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée la table riasec_test_sessions — cœur du moteur adaptatif.
 *
 * Cette table représente une session de test unique (auth ou invité).
 * Elle stocke l'état complet du test en cours :
 *   - Questions déjà posées (administered_question_ids)
 *   - Scores provisoires calculés en temps réel (current_scores)
 *   - Phase du test (seed → adaptive)
 *   - Métriques de précision pour l'arrêt anticipé
 *   - Données démographiques optionnelles pour la stratification
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riasec_test_sessions', function (Blueprint $table) {

            $table->id();

            // ── Identification de la session ──────────────────────────────
            $table->uuid('session_token')
                  ->unique()
                  ->comment('UUID v4 unique par session de test');

            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->comment('NULL si utilisateur invité');

            $table->string('session_guest_id', 100)
                  ->nullable()
                  ->index()
                  ->comment('ID de session PHP pour les invités');

            // ── Données démographiques ────────────────────────────────────
            // Permettent la stratification des résultats (âge, bac, région)
            $table->json('demographic_data')
                  ->nullable()
                  ->comment('JSON: {age, bac_type, region, langue, ...}');

            // ── État du moteur adaptatif ───────────────────────────────────
            // Scores provisoires recalculés après chaque réponse (ou lot de 4)
            $table->json('current_scores')
                  ->nullable()
                  ->comment('JSON: {R:45.5, I:72.3, A:31.0, S:58.7, E:62.1, C:40.2}');

            // IDs des questions déjà administrées (pour éviter les répétitions)
            $table->json('administered_question_ids')
                  ->nullable()
                  ->comment('Liste des IDs de questions posées dans cette session');

            // Snapshot des scores à chaque recalcul (utile pour détecter la convergence)
            $table->json('scores_history')
                  ->nullable()
                  ->comment('Historique des scores: [{step:4, R:40, I:65,...}, ...]');

            // ── Phase du test ─────────────────────────────────────────────
            $table->unsignedTinyInteger('phase')
                  ->default(1)
                  ->comment('1=seed (12q), 2=adaptive (IRT)');

            $table->boolean('seed_phase_complete')
                  ->default(false)
                  ->comment('Vrai quand les 12 questions seed ont été posées');

            // ── Compteurs de progression ───────────────────────────────────
            $table->unsignedSmallInteger('total_questions_asked')
                  ->default(0);

            $table->unsignedSmallInteger('min_questions')
                  ->default(30)
                  ->comment('Minimum de questions avant arrêt possible');

            $table->unsignedSmallInteger('max_questions')
                  ->default(55)
                  ->comment('Maximum absolu de questions (hard stop)');

            // ── Métriques de précision ────────────────────────────────────
            // Score de précision 0-100 : plus haut = scores plus stables
            $table->float('precision_score', 5, 2)
                  ->nullable()
                  ->comment('Stabilité des scores (0=volatile, 100=stable)');

            // Variance moyenne des scores sur les 6 dernières réponses
            $table->float('score_variance', 5, 2)
                  ->nullable()
                  ->comment('Variance moy. des scores dans la dernière fenêtre');

            // Score de cohérence (détection biais de désirabilité sociale)
            $table->float('coherence_score', 5, 2)
                  ->nullable()
                  ->comment('Cohérence des réponses (0=incohérent, 100=très cohérent)');

            // ── Arrêt du test ─────────────────────────────────────────────
            $table->string('stop_reason', 60)
                  ->nullable()
                  ->comment('Raison: max_reached|precision_achieved|min_dims_met|abandoned');

            $table->enum('statut', ['en_cours', 'complet', 'abandon', 'expire'])
                  ->default('en_cours')
                  ->index();

            // ── Horodatage ────────────────────────────────────────────────
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // ── Index composites ──────────────────────────────────────────
            $table->index(['user_id', 'statut'], 'idx_user_statut');
            $table->index(['session_token', 'statut'], 'idx_token_statut');
            $table->index('started_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riasec_test_sessions');
    }
};
