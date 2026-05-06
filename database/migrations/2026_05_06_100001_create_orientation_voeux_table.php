<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table des vœux d'orientation.
 * Un étudiant peut sauvegarder des filières favorites avec un ordre de priorité.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orientation_voeux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete()
                  ->comment('Étudiant propriétaire du vœu');
            $table->foreignId('formation_id')
                  ->constrained()
                  ->cascadeOnDelete()
                  ->comment('Filière concernée');
            $table->unsignedTinyInteger('priorite')->default(0)
                  ->comment('Ordre de priorité (0=non classé, 1=1er choix, etc.)');
            $table->text('notes_perso')->nullable()
                  ->comment('Notes personnelles de l\'étudiant sur ce vœu');
            $table->boolean('est_confirme')->default(false)
                  ->comment('Vœu officiellement confirmé');
            $table->timestamps();

            // Un étudiant ne peut avoir qu'un seul vœu par formation
            $table->unique(['user_id', 'formation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orientation_voeux');
    }
};
