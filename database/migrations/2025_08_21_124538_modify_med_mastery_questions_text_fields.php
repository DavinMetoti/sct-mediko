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
            // Mengubah question_text dari string ke longText untuk menampung konten rich text editor
            $table->longText('question_text')->change();
            
            // Mengubah explanation dari string ke longText untuk menampung konten rich text editor yang lebih panjang
            $table->longText('explanation')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('med_mastery_questions', function (Blueprint $table) {
            // Mengembalikan ke tipe data string (255 karakter)
            $table->string('question_text')->change();
            $table->string('explanation')->nullable()->change();
        });
    }
};
