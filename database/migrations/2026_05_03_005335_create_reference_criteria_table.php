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
        Schema::create('reference_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reference_section_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->decimal('coefficient', 4, 2)->default(1.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reference_criteria');
    }
};
