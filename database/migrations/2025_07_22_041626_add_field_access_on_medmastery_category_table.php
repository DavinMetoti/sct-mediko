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
        Schema::table('medmastery_category', function (Blueprint $table) {
            $table->enum('access', ['public', 'private'])->default('public')->after('created_by')->comment('Access level of the category: public (visible to all) or private (visible only to creator)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medmastery_category', function (Blueprint $table) {
            $table->dropColumn('access');
        });
    }
};
