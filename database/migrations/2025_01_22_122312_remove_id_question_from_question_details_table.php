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
        Schema::table('question_details', function (Blueprint $table) {
            $table->dropForeign(['id_question']);
            $table->dropColumn('id_question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id_question')->nullable();
            $table->foreign('id_question')->references('id')->on('questions')->onDelete('cascade');
        });
    }
};
