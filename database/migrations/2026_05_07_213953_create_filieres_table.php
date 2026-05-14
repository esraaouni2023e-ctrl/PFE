<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table `filieres` qui centralise toutes les filières universitaires
     * tunisiennes importées depuis les fichiers Excel par catégorie.
     */
    public function up(): void
    {
        Schema::create('filieres', function (Blueprint $table) {
            $table->id();
            $table->string('code_filiere')->unique();
            $table->string('nom_filiere');
            $table->string('universite')->nullable();
            $table->string('etablissement')->nullable();
            $table->decimal('sdo_2023', 8, 2)->nullable();
            $table->decimal('sdo_2024', 8, 2)->nullable();
            $table->decimal('sdo_2025', 8, 2)->nullable();
            $table->string('domaine')->nullable();
            
            // Prérequis GATB (sur 20)
            $table->integer('g_requis')->default(10);
            $table->integer('v_requis')->default(10);
            $table->integer('n_requis')->default(10);
            $table->integer('s_requis')->default(10);
            
            $table->timestamps();
        });
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('filieres');
    }
};
