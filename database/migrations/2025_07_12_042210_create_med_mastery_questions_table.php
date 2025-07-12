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
        Schema::create('med_mastery_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medmastery_category_id');
            $table->string('question_text');
            $table->string('explanation')->nullable();
            $table->string('explanation_pdf_path')->nullable()->comment('Path to the PDF explanation');
            $table->unsignedBigInteger('creator_id')->comment('User ID of the creator');
            $table->boolean('is_active')->default(true)->comment('Indicates if the question is active');
            $table->timestamps();
        });
        
        Schema::table('med_mastery_questions', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade')->comment('Foreign key referencing the users table');
            $table->foreign('medmastery_category_id')
                ->references('id')
                ->on('medmastery_category')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('med_mastery_questions');
    }
};
