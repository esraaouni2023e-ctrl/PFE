<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('profiles')) {
            Schema::create('profiles', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->text('skills')->nullable();
                $table->text('interests')->nullable();
                $table->text('strengths')->nullable();
                $table->float('ai_score', 5, 2)->nullable();
                $table->text('summary')->nullable();
                $table->integer('total_xp')->default(0);
                $table->text('settings')->nullable();
                $table->timestamps();

                $table->unique('user_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
