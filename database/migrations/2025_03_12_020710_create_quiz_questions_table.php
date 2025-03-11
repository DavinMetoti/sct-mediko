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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_question_bank_id')->constrained()->onDelete('cascade');
            $table->foreignId('medical_field_id')->constrained()->onDelete('cascade');
            $table->foreignId('column_title_id')->constrained()->onDelete('cascade');
            $table->text('clinical_case');
            $table->string('initial_hypothesis');
            $table->string('new_information');
            $table->integer('timer');
            $table->text('explanation')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
