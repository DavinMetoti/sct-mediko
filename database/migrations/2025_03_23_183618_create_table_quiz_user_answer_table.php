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
        Schema::create('quiz_user_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_attempts_id');
            $table->unsignedBigInteger('quiz_question_id');
            $table->unsignedBigInteger('quiz_answer_id');
            $table->decimal('score', 8, 2);
            $table->timestamps();

            $table->foreign('quiz_attempts_id')->references('id')->on('quiz_attempts')->onDelete('cascade');
            $table->foreign('quiz_question_id')->references('id')->on('quiz_questions')->onDelete('cascade');
            $table->foreign('quiz_answer_id')->references('id')->on('quiz_answers')->onDelete('cascade');

            $table->index(['quiz_attempts_id', 'quiz_question_id', 'quiz_answer_id'], 'quiz_answer_idx');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_user_answers');
    }
};
