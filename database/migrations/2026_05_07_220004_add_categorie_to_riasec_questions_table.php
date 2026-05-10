<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute la colonne `categorie` à la table riasec_questions.
 *
 * Catégories disponibles :
 *  - loisirs                      → ce que le lycéen aime faire hors école
 *  - preferences_professionnelles → attrait pour certains types de métiers
 *  - qualites_personnelles        → traits de caractère et comportements
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riasec_questions', function (Blueprint $table) {
            $table->enum('categorie', [
                'loisirs',
                'preferences_professionnelles',
                'qualites_personnelles',
            ])->default('loisirs')
              ->after('dimension')
              ->comment('Famille thématique de la question');

            $table->index(['dimension', 'categorie'], 'idx_dim_cat');
        });
    }

    public function down(): void
    {
        Schema::table('riasec_questions', function (Blueprint $table) {
            $table->dropIndex('idx_dim_cat');
            $table->dropColumn('categorie');
        });
    }
};
