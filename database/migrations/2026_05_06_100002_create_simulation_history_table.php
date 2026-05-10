<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table d'historique des simulations What-If.
 * Chaque simulation est sauvegardée pour permettre la consultation ultérieure.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulation_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('section_bac', 50)
                  ->comment('Section BAC utilisée pour la simulation');
            $table->decimal('moyenne_generale', 4, 2)
                  ->comment('Moyenne générale entrée');
            $table->json('notes_matieres')
                  ->comment('Notes par matière en JSON');
            $table->decimal('score_fg', 6, 2)
                  ->comment('Score FG calculé');
            $table->json('formations_accessibles')->nullable()
                  ->comment('Liste des formations accessibles avec ce score');
            $table->string('label', 100)->nullable()
                  ->comment('Label personnalisé de la simulation (ex: "Scénario optimiste")');
            $table->timestamps();

            // Index pour les requêtes fréquentes
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_history');
    }
};
