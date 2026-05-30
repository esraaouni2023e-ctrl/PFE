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
        Schema::table('filiere_profiles', function (Blueprint $table) {
            $table->float('job_demand', 4, 2)->default(0.60)->after('stress_tolerance');
            $table->float('salary', 4, 2)->default(0.60)->after('job_demand');
            $table->float('internships', 4, 2)->default(0.60)->after('salary');
            $table->string('market_source', 100)->default('INS Tunisia / ANETI')->after('internships');
            $table->string('market_date', 20)->default('2026-05')->after('market_source');
            $table->string('market_region', 50)->default('Tunisie')->after('market_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filiere_profiles', function (Blueprint $table) {
            $table->dropColumn(['job_demand', 'salary', 'internships', 'market_source', 'market_date', 'market_region']);
        });
    }
};
