<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riasec_questions', function (Blueprint $table) {
            // Agrandir dimension pour accepter les codes multi-blocs (ex: Num, Sp, Sec, Ach...)
            $table->string('dimension', 10)->change();
        });
    }

    public function down(): void
    {
        Schema::table('riasec_questions', function (Blueprint $table) {
            $table->char('dimension', 1)->change();
        });
    }
};
