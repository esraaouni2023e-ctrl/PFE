<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add is_admin column
        if (! Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(false)->after('role');
            });
        }

        // Set is_admin = true for existing admin role users
        DB::table('users')->where('role', 'admin')->update([
            'is_admin' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert is_admin users back to admin role
        DB::table('users')->where('is_admin', true)->update(['role' => 'admin']);

        if (Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin');
            });
        }
    }
};
