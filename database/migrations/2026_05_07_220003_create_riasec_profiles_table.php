<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table `riasec_profiles` — profil RIASEC calculé après complétion d'un test.
 *
 * Un profil est généré à partir d'une session de test (test_session_id).
 * Il stocke les scores bruts par dimension, le code Holland calculé (ex: "IAS"),
 * ainsi que les métadonnées d'interprétation.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riasec_profiles', function (Blueprint $table) {
            // ── Identifiant ───────────────────────────────────────────────
            $table->id();

            // ── Lien session ──────────────────────────────────────────────
            $table->uuid('test_session_id')->unique()
                  ->comment('Lien avec la session de test (riasec_answers)');

            // ── Utilisateur (nullable pour invités) ──────────────────────
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->string('session_guest_id', 64)->nullable()->index();

            // ── Scores bruts par dimension (0–100) ───────────────────────
            $table->unsignedTinyInteger('score_r')->default(0)->comment('Réaliste');
            $table->unsignedTinyInteger('score_i')->default(0)->comment('Investigateur');
            $table->unsignedTinyInteger('score_a')->default(0)->comment('Artistique');
            $table->unsignedTinyInteger('score_s')->default(0)->comment('Social');
            $table->unsignedTinyInteger('score_e')->default(0)->comment('Entreprenant');
            $table->unsignedTinyInteger('score_c')->default(0)->comment('Conventionnel');

            // ── Code Holland (3 lettres dominant) ────────────────────────
            // Ex: "IAS" = Investigateur > Artistique > Social
            $table->char('code_holland', 3)->index()
                  ->comment('3 lettres dominantes triées par score décroissant');

            // ── Statut du test ────────────────────────────────────────────
            $table->enum('statut', ['en_cours', 'complet', 'expire'])
                  ->default('en_cours')->index();

            // ── Progression ───────────────────────────────────────────────
            $table->unsignedTinyInteger('nb_questions_repondues')->default(0);
            $table->unsignedTinyInteger('nb_questions_total')->default(0);

            // ── Métadonnées de qualité ────────────────────────────────────
            // Score de cohérence interne (0-100) calculé par le service
            $table->unsignedTinyInteger('score_coherence')->nullable()
                  ->comment('Indice de fiabilité des réponses 0-100');

            // ── Résultat enrichi (JSON) ───────────────────────────────────
            // Stocke les recommandations filières, descriptif du profil, etc.
            $table->json('interpretation')->nullable()
                  ->comment('Texte profil, filières suggérées, métiers compatibles');

            // ── Durée totale du test ──────────────────────────────────────
            $table->unsignedSmallInteger('duree_minutes')->nullable();

            $table->timestamp('complete_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riasec_profiles');
    }
};
