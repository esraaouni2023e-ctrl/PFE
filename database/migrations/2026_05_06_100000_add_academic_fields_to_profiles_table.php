<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute les champs académiques au profil étudiant.
 * Permet de stocker les informations du BAC et le score FG calculé.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Données académiques
            if (!Schema::hasColumn('profiles', 'section_bac')) {
                $table->string('section_bac', 50)->nullable()->after('settings')
                      ->comment('Section du Baccalauréat (Mathématiques, Sciences, etc.)');
            }
            if (!Schema::hasColumn('profiles', 'moyenne_generale')) {
                $table->decimal('moyenne_generale', 4, 2)->nullable()->after('section_bac')
                      ->comment('Moyenne générale du BAC (0-20)');
            }
            if (!Schema::hasColumn('profiles', 'annee_bac')) {
                $table->smallInteger('annee_bac')->nullable()->after('moyenne_generale')
                      ->comment('Année du Baccalauréat');
            }
            if (!Schema::hasColumn('profiles', 'gouvernorat')) {
                $table->string('gouvernorat', 100)->nullable()->after('annee_bac')
                      ->comment('Gouvernorat de résidence');
            }
            if (!Schema::hasColumn('profiles', 'notes_matieres')) {
                $table->json('notes_matieres')->nullable()->after('gouvernorat')
                      ->comment('Notes détaillées par matière (JSON)');
            }
            if (!Schema::hasColumn('profiles', 'score_fg')) {
                $table->decimal('score_fg', 6, 2)->nullable()->after('notes_matieres')
                      ->comment('Score Formule Globale calculé');
            }
            if (!Schema::hasColumn('profiles', 'score_fg_updated_at')) {
                $table->timestamp('score_fg_updated_at')->nullable()->after('score_fg')
                      ->comment('Date du dernier calcul FG');
            }
            // Statut supplémentaire
            if (!Schema::hasColumn('profiles', 'counselor_observations')) {
                $table->text('counselor_observations')->nullable()->after('score_fg_updated_at');
            }
            if (!Schema::hasColumn('profiles', 'coaching_plan')) {
                $table->text('coaching_plan')->nullable()->after('counselor_observations');
            }
            if (!Schema::hasColumn('profiles', 'status')) {
                $table->string('status', 30)->default('active')->after('coaching_plan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $cols = ['section_bac', 'moyenne_generale', 'annee_bac', 'gouvernorat',
                     'notes_matieres', 'score_fg', 'score_fg_updated_at'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('profiles', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
