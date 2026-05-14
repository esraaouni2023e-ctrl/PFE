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
        Schema::table('riasec_profiles', function (Blueprint $table) {
            $table->unsignedTinyInteger('confidence_score')->nullable()->after('score_coherence');
            $table->boolean('stopped_early')->default(false)->after('confidence_score');
            $table->unsignedTinyInteger('blocks_completed')->default(0)->after('stopped_early');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riasec_profiles', function (Blueprint $table) {
            //
        });
    }
};
