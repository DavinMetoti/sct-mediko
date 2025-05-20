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
        if (Schema::hasTable('quiz_questions')) {
            Schema::table('quiz_questions', function (Blueprint $table) {
                if (Schema::hasColumn('quiz_questions', 'clinical_case')) {
                    // Kolom ada → ubah ke longText
                    $table->longText('clinical_case')->change();
                } else {
                    // Kolom tidak ada → tambahkan baru
                    $table->longText('clinical_case')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('quiz_questions') && Schema::hasColumn('quiz_questions', 'clinical_case')) {
            Schema::table('quiz_questions', function (Blueprint $table) {
                $table->dropColumn('clinical_case');
            });
        }
    }
};
