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
        Schema::create('question_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_question');
            $table->unsignedBigInteger('id_medical_field');
            $table->text('clinical_case')->nullable();
            $table->text('initial_hypothesis')->nullable();
            $table->text('new_information')->nullable();
            $table->longText('discussion_image')->nullable();
            $table->json('panelist_answers_distribution')->nullable();
            $table->timestamps();

            // Tambahkan foreign key constraints
            $table->foreign('id_question')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('id_medical_field')->references('id')->on('medical_fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_details');
    }
};
