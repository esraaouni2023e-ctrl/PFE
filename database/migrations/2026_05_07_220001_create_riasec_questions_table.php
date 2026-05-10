<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table `riasec_questions` — banque de questions du test Holland RIASEC.
 *
 * Chaque question est associée à une dimension RIASEC (R/I/A/S/E/C),
 * possède un type de réponse configurable, et peut être localisée.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riasec_questions', function (Blueprint $table) {
            // ── Identifiant ───────────────────────────────────────────────
            $table->id();

            // ── Dimension RIASEC ─────────────────────────────────────────
            // R=Réaliste, I=Investigateur, A=Artistique, S=Social, E=Entreprenant, C=Conventionnel
            $table->char('dimension', 1)->comment('R | I | A | S | E | C');
            $table->index('dimension');

            // ── Contenu de la question ───────────────────────────────────
            $table->text('texte_fr')->comment('Libellé en français');
            $table->text('texte_ar')->nullable()->comment('Libellé en arabe (optionnel)');

            // ── Type de réponse ──────────────────────────────────────────
            // likert   : échelle 1-5 (Pas du tout → Tout à fait)
            // boolean  : Oui / Non
            // choice   : QCM avec options JSON
            $table->enum('type_reponse', ['likert', 'boolean', 'choice'])
                  ->default('likert');

            // Options JSON pour type_reponse = 'choice'
            // Ex: [{"valeur": 1, "label": "Jamais"}, {"valeur": 5, "label": "Toujours"}]
            $table->json('options')->nullable();

            // ── Pondération & ordre ──────────────────────────────────────
            // Certaines questions comptent davantage (ex: questions validées psychométriquement)
            $table->unsignedTinyInteger('poids')->default(1)->comment('Pondération 1-3');
            $table->unsignedSmallInteger('ordre')->default(0)->comment('Ordre d\'affichage');

            // ── État ─────────────────────────────────────────────────────
            $table->boolean('actif')->default(true)->index();

            // ── Méta ─────────────────────────────────────────────────────
            $table->string('source')->nullable()->comment('Référence psychométrique (ex: Holland 1973)');
            $table->string('version', 10)->default('1.0');

            $table->timestamps();

            // Index composite pour récupérer rapidement les questions actives d'une dimension
            $table->index(['dimension', 'actif', 'ordre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riasec_questions');
    }
};
