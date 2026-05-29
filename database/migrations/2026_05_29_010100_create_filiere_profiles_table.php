<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SIAEPI v5.0 — Table filiere_profiles
     *
     * Stocke les profils psychométriques cibles de chaque filière,
     * permettant au moteur de charger dynamiquement les exigences
     * au lieu de les coder en dur en PHP.
     */
    public function up(): void
    {
        if (!Schema::hasTable('filiere_profiles')) {
            Schema::create('filiere_profiles', function (Blueprint $table) {
                $table->id();
                $table->string('code_filiere')->unique();
                $table->string('nom_filiere');
                $table->string('domaine')->nullable();          // informatique, sante, technique...

                // Profil RIASEC cible (0.0 – 1.0)
                $table->float('riasec_r', 4, 2)->default(0.50);
                $table->float('riasec_i', 4, 2)->default(0.50);
                $table->float('riasec_a', 4, 2)->default(0.50);
                $table->float('riasec_s', 4, 2)->default(0.50);
                $table->float('riasec_e', 4, 2)->default(0.50);
                $table->float('riasec_c', 4, 2)->default(0.50);

                // Aptitudes GATB requises (0 – 100)
                $table->tinyInteger('gatb_g_required')->default(50);
                $table->tinyInteger('gatb_v_required')->default(50);
                $table->tinyInteger('gatb_n_required')->default(50);
                $table->tinyInteger('gatb_s_required')->default(50);

                // Indices marché et difficulté
                $table->float('employability_index', 3, 2)->default(0.60); // 0.0 – 1.0
                $table->tinyInteger('difficulty_level')->default(5);        // 1 – 10
                $table->tinyInteger('stress_tolerance')->default(5);        // 1 – 10

                // Big Five idéal (z-score, -3 à +3)
                $table->float('big5_openness', 4, 2)->default(0.00);
                $table->float('big5_conscientiousness', 4, 2)->default(0.00);
                $table->float('big5_extraversion', 4, 2)->default(0.00);
                $table->float('big5_agreeableness', 4, 2)->default(0.00);
                $table->float('big5_neuroticism', 4, 2)->default(0.00);

                $table->text('description')->nullable();
                $table->timestamps();

                $table->index('domaine');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filiere_profiles');
    }
};
