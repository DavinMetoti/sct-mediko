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
            $table->unsignedBigInteger('id_question'); // Foreign key ke tabel questions
            $table->unsignedBigInteger('id_medical_field'); // Foreign key ke tabel medical_fields
            $table->text('clinical_case')->nullable(); // Kasus Klinis
            $table->text('initial_hypothesis')->nullable(); // Hipotesis Awal
            $table->text('new_information')->nullable(); // Informasi Baru
            $table->longText('discussion_image')->nullable(); // Gambar Pembahasan (Base64)
            $table->json('panelist_answers_distribution')->nullable(); // Distribusi Jawaban Panelis (dalam JSON)
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
