<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute les champs nécessaires au moteur adaptatif IRT (Item Response Theory)
 * sur la table riasec_questions.
 *
 * Paramètres IRT Modèle 2PL (2-Parameter Logistic) :
 *   a (discrimination) : capacité de la question à différencier les profils
 *   b (difficulty)     : niveau de trait (theta) auquel P(réponse=5) = 0.5
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riasec_questions', function (Blueprint $table) {

            // ── Paramètre IRT : difficulté (b-parameter) ──────────────────
            // Plage : 1.0 (facile — accessible à tous) → 5.0 (difficile — trait fort requis)
            // Défaut : 3.0 = question de difficulté moyenne, calibrage centre
            $table->float('difficulty', 4, 2)
                  ->default(3.00)
                  ->after('source')
                  ->comment('IRT b-param : niveau de trait requis (1=easy,5=hard)');

            // ── Paramètre IRT : discrimination (a-parameter) ───────────────
            // Plage : 0.5 (peu discriminant) → 3.0 (très discriminant)
            // Défaut : 1.0 (discrimination standard OIT/Holland)
            $table->float('discrimination', 4, 2)
                  ->default(1.00)
                  ->after('difficulty')
                  ->comment('IRT a-param : pouvoir discriminant de la question');

            // ── Question inversée ─────────────────────────────────────────
            // Si true : score inversé (6 - valeur) avant calcul
            // Permet la détection de social desirability bias
            $table->boolean('is_reverse')
                  ->default(false)
                  ->after('discrimination')
                  ->comment('Si vrai, le score est inversé (6-valeur) pour la cohérence');

            // ── Version de calibrage ───────────────────────────────────────
            // Permet de versionner les paramètres IRT quand on re-calibre
            $table->string('calibration_version', 10)
                  ->nullable()
                  ->after('is_reverse')
                  ->comment('Version du calibrage IRT (ex: v1.0, v2.3)');

            // ── Phase de seed ─────────────────────────────────────────────
            // Indique si cette question fait partie des questions initiales (seed phase)
            // Les questions seed sont sélectionnées en premier pour couvrir toutes les dimensions
            $table->boolean('is_seed')
                  ->default(false)
                  ->after('calibration_version')
                  ->comment('Si vrai, question utilisée en phase seed (2 par dimension)');

            // ── Index de performance ──────────────────────────────────────
            $table->index(['dimension', 'difficulty', 'actif'], 'idx_dim_diff_actif');
            $table->index(['is_seed', 'actif'], 'idx_seed_actif');
            $table->index(['discrimination', 'actif'], 'idx_disc_actif');
        });
    }

    public function down(): void
    {
        Schema::table('riasec_questions', function (Blueprint $table) {
            $table->dropIndex('idx_dim_diff_actif');
            $table->dropIndex('idx_seed_actif');
            $table->dropIndex('idx_disc_actif');
            $table->dropColumn([
                'difficulty', 'discrimination',
                'is_reverse', 'calibration_version', 'is_seed',
            ]);
        });
    }
};
