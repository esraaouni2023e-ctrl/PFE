<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riasec_questions', function (Blueprint $table) {
            // Bloc psychométrique : riasec | big_five | gatb | schwartz
            $table->string('bloc', 20)->default('riasec')->after('dimension')
                  ->comment('riasec | big_five | gatb | schwartz');
            $table->index('bloc');
        });
    }

    public function down(): void
    {
        Schema::table('riasec_questions', function (Blueprint $table) {
            $table->dropColumn('bloc');
        });
    }
};
