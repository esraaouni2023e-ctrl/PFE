<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('test_attempts')) {
            Schema::create('test_attempts', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('test_id');
                $table->decimal('score', 6, 2)->nullable();
                $table->text('answers')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->integer('duration_seconds')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'test_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('test_attempts');
    }
};
