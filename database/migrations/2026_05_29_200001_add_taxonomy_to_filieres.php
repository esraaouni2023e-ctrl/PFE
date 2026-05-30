<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modifier la table filieres
        Schema::table('filieres', function (Blueprint $table) {
            if (!Schema::hasColumn('filieres', 'domaine_id')) {
                $table->foreignId('domaine_id')->nullable()->after('domaine')->constrained('domaines')->onDelete('set null');
            }
            if (!Schema::hasColumn('filieres', 'sous_domaine_id')) {
                $table->foreignId('sous_domaine_id')->nullable()->after('domaine_id')->constrained('sous_domaines')->onDelete('set null');
            }
            if (!Schema::hasColumn('filieres', 'specialisation_id')) {
                $table->foreignId('specialisation_id')->nullable()->after('sous_domaine_id')->constrained('specialisations')->onDelete('set null');
            }
        });

        // 2. Modifier la table filiere_profiles
        Schema::table('filiere_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('filiere_profiles', 'employability_rate')) {
                $table->float('employability_rate', 5, 2)->nullable()->after('employability_index');
            }
            if (!Schema::hasColumn('filiere_profiles', 'growth_rate')) {
                $table->float('growth_rate', 5, 2)->nullable()->after('employability_rate');
            }
            if (!Schema::hasColumn('filiere_profiles', 'annual_openings')) {
                $table->integer('annual_openings')->nullable()->after('growth_rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filieres', function (Blueprint $table) {
            $table->dropForeign(['specialisation_id']);
            $table->dropForeign(['sous_domaine_id']);
            $table->dropForeign(['domaine_id']);
            $table->dropColumn(['domaine_id', 'sous_domaine_id', 'specialisation_id']);
        });

        Schema::table('filiere_profiles', function (Blueprint $table) {
            $table->dropColumn(['employability_rate', 'growth_rate', 'annual_openings']);
        });
    }
};
