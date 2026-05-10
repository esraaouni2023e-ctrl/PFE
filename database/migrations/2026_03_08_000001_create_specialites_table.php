<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specialites', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description');
            $table->string('domaine'); // Sciences, Technologie, Arts, Gestion, Santé, Économie, Droit, Éducation
            $table->string('icon')->default('📚');
            $table->string('color')->default('indigo'); // indigo, cyan, violet, green, amber
            $table->integer('nb_formations')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialites');
    }
};
