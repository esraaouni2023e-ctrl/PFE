<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table `riasec_answers` — réponses individuelles à chaque question du test.
 *
 * Supporte les utilisateurs authentifiés (user_id) ET les invités (session_id).
 * Une session de test est identifiée par `test_session_id` (UUID v4).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riasec_answers', function (Blueprint $table) {
            // ── Identifiant ───────────────────────────────────────────────
            $table->id();

            // ── Session de test ──────────────────────────────────────────
            // UUID unique par passage du test (permet grouper toutes les réponses d'un test)
            $table->uuid('test_session_id')->index();

            // ── Utilisateur (authentifié ou invité) ──────────────────────
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->comment('NULL si utilisateur invité');

            $table->string('session_guest_id', 64)
                  ->nullable()
                  ->index()
                  ->comment('ID de session PHP pour les invités');

            // ── Question liée ─────────────────────────────────────────────
            $table->foreignId('question_id')
                  ->constrained('riasec_questions')
                  ->cascadeOnDelete();

            // ── Valeur de la réponse ─────────────────────────────────────
            // Entier : 1-5 (likert), 0/1 (boolean), ou indice de choix (choice)
            $table->unsignedTinyInteger('valeur')
                  ->comment('1-5 pour likert, 0/1 pour boolean, index pour choice');

            // ── Temps de réponse (UX & analytics) ───────────────────────
            $table->unsignedSmallInteger('temps_reponse_ms')
                  ->nullable()
                  ->comment('Durée de réflexion en millisecondes');

            $table->timestamps();

            // ── Contrainte unicité : une réponse par question par session ─
            $table->unique(['test_session_id', 'question_id'], 'unique_session_question');

            // Index de récupération rapide
            $table->index(['user_id', 'test_session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riasec_answers');
    }
};
