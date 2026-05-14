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
        Schema::table('riasec_profiles', function (Blueprint $table) {
            $table->boolean('is_flagged')->default(false)->after('blocks_completed');
            $table->string('validation_status')->default('auto_approved')->after('is_flagged'); // auto_approved, pending_manual_review
            $table->json('flag_reason')->nullable()->after('validation_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riasec_profiles', function (Blueprint $table) {
            $table->dropColumn(['is_flagged', 'validation_status', 'flag_reason']);
        });
    }
};
