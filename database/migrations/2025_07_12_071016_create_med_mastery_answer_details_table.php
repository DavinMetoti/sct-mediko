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
        Schema::create('med_mastery_answer_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('med_mastery_question_id')->constrained('med_mastery_questions')->onDelete('cascade');
            $table->foreignId('med_mastery_answer_id')->constrained('med_mastery_answers')->onDelete('cascade');
            $table->string('answer_text')->comment('Text of the answer');
            $table->double('score')->nullable()->comment('Student score');
            $table->boolean('is_correct')->default(false)->comment('Indicates if the answer is correct');
            $table->enum('self_assessment', ['salah', 'hampir_benar', 'benar'])->nullable()->comment('Student self-assessment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('med_mastery_answer_details');
    }
};
