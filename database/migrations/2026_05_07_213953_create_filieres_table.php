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
            // ── Identifiants ──────────────────────────────────────────────
            $table->id();

            // Code unique de la filière (clé métier, ex: "INFO_001")
            $table->string('code_filiere', 50)->unique();

            // Catégorie d'import : INFO, TECH, ECO, EXP, SPORT, MAT, LET
            $table->string('categorie', 20)->index();

            // ── Informations descriptives ─────────────────────────────────
            $table->string('nom_filiere');
            $table->string('universite')->nullable();
            $table->string('etablissement')->nullable();

            // ── Scores d'orientation (SDO) ────────────────────────────────
            // Null = donnée non disponible pour cette année
            $table->decimal('sdo_2023', 5, 2)->nullable();
            $table->decimal('sdo_2024', 5, 2)->nullable();
            $table->decimal('sdo_2025', 5, 2)->nullable();

            // ── Profil RIASEC ─────────────────────────────────────────────
            // Code Holland sur 6 lettres, ex: "RIA", "ESC"
            $table->string('code_riasec', 6)->nullable()->index();

            // ── Métriques d'employabilité ─────────────────────────────────
            // Pourcentages stockés en décimal (ex: 0.87 = 87%)
            $table->decimal('taux_employabilite', 5, 2)->nullable();
            $table->decimal('croissance_domaine', 5, 2)->nullable();
            $table->decimal('alignment_national', 5, 2)->nullable();

            // ── Traçabilité ───────────────────────────────────────────────
            // Fichier source d'origine
            $table->string('source')->nullable();

            $table->timestamps();

            // ── Indexes pour les recherches fréquentes ────────────────────
            $table->index(['categorie', 'code_riasec']);
            $table->index('taux_employabilite');
            $table->index('sdo_2025');
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
