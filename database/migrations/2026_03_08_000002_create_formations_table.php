<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialite_id')->constrained('specialites')->onDelete('cascade');
            $table->string('nom');
            $table->string('etablissement');
            $table->string('ville');
            $table->string('duree'); // "3 ans", "5 ans"
            $table->string('niveau'); // Licence, Master, Ingénierie, Doctorat
            $table->text('description');
            $table->text('debouches');
            $table->text('conditions_acces');
            $table->string('salaire_min')->nullable(); // "1 800 DT"
            $table->string('salaire_max')->nullable(); // "4 500 DT"
            $table->string('secteur');
            $table->string('icon')->default('📖');
            $table->integer('score_matching')->default(75);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
