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
        // 1. Table Domaines
        if (!Schema::hasTable('domaines')) {
            Schema::create('domaines', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('nom');
                $table->text('description')->nullable();
                $table->string('icon')->default('📚');
                $table->timestamps();
            });
        }

        // 2. Table Sous-domaines
        if (!Schema::hasTable('sous_domaines')) {
            Schema::create('sous_domaines', function (Blueprint $table) {
                $table->id();
                $table->foreignId('domaine_id')->constrained('domaines')->onDelete('cascade');
                $table->string('code')->unique();
                $table->string('nom');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 3. Table Spécialisations
        if (!Schema::hasTable('specialisations')) {
            Schema::create('specialisations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sous_domaine_id')->constrained('sous_domaines')->onDelete('cascade');
                $table->string('code')->unique();
                $table->string('nom');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 4. Table Métiers associés
        if (!Schema::hasTable('metiers')) {
            Schema::create('metiers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('specialisation_id')->constrained('specialisations')->onDelete('cascade');
                $table->string('title');
                $table->text('description');
                $table->string('salary_range')->nullable();
                $table->string('employability')->nullable();
                $table->json('secteurs')->nullable();
                $table->json('skills_hard')->nullable();
                $table->json('skills_soft')->nullable();
                $table->text('perspectives')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metiers');
        Schema::dropIfExists('specialisations');
        Schema::dropIfExists('sous_domaines');
        Schema::dropIfExists('domaines');
    }
};
