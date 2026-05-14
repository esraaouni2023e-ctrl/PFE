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
        Schema::table('riasec_questions', function (Blueprint $table) {
            $table->string('categorie', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riasec_questions', function (Blueprint $table) {
            // Can't revert to ENUM safely if values don't match, just leave as string or omit
        });
    }
};
