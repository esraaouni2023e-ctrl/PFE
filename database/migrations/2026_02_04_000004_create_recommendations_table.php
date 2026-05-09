<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('recommendations')) {
            Schema::create('recommendations', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->text('data')->nullable();
                $table->string('source')->nullable();
                $table->float('relevance', 5, 2)->default(0);
                $table->integer('created_by')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'relevance']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
