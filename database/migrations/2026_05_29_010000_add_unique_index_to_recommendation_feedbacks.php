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
        if (Schema::hasTable('recommendation_feedbacks')) {
            Schema::table('recommendation_feedbacks', function (Blueprint $table) {
                // Garantie unicité : un seul feedback par (user, filière)
                // updateOrCreate gère côté applicatif, ceci ajoute la sécurité BDD
                $table->unique(['user_id', 'filiere_code'], 'uq_user_filiere_feedback');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('recommendation_feedbacks')) {
            Schema::table('recommendation_feedbacks', function (Blueprint $table) {
                $table->dropUnique('uq_user_filiere_feedback');
            });
        }
    }
};
