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
        Schema::table('filieres', function (Blueprint $table) {
            if (!Schema::hasColumn('filieres', 'code_riasec')) {
                $table->string('code_riasec', 20)->nullable()->after('domaine');
            }
            if (!Schema::hasColumn('filieres', 'taux_employabilite')) {
                $table->string('taux_employabilite', 50)->nullable()->after('code_riasec');
            }
            if (!Schema::hasColumn('filieres', 'croissance_domaine')) {
                $table->string('croissance_domaine', 50)->nullable()->after('taux_employabilite');
            }
            if (!Schema::hasColumn('filieres', 'type_bac')) {
                $table->string('type_bac', 100)->nullable()->after('croissance_domaine');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filieres', function (Blueprint $table) {
            $table->dropColumn(['code_riasec', 'taux_employabilite', 'croissance_domaine', 'type_bac']);
        });
    }
};
