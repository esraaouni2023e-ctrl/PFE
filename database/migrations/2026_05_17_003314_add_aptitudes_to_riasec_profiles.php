<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riasec_profiles', function (Blueprint $table) {
            // Aptitudes GATB (0-100)
            // On vérifie si les colonnes n'existent pas déjà (sécurité)
            if (!Schema::hasColumn('riasec_profiles', 'score_gatb_g')) {
                $table->unsignedTinyInteger('score_gatb_g')->default(0)->after('score_c');
                $table->unsignedTinyInteger('score_gatb_v')->default(0)->after('score_gatb_g');
                $table->unsignedTinyInteger('score_gatb_n')->default(0)->after('score_gatb_v');
                $table->unsignedTinyInteger('score_gatb_s')->default(0)->after('score_gatb_n');
            }

            // Résilience & Persévérance
            if (!Schema::hasColumn('riasec_profiles', 'score_resilience')) {
                $table->unsignedTinyInteger('score_resilience')->default(0)->after('score_gatb_s');
            }
        });
    }

    public function down(): void
    {
        Schema::table('riasec_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'score_gatb_g', 'score_gatb_v', 'score_gatb_n', 'score_gatb_s',
                'score_resilience'
            ]);
        });
    }
};
