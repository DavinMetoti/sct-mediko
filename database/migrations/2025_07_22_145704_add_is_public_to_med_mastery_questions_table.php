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
        Schema::table('med_mastery_questions', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('is_active')->comment('Indicates if the question is public (accessible by all users)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('med_mastery_questions', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }
};
