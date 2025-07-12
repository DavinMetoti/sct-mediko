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
        Schema::create('med_mastery_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('med_mastery_category_id')->constrained('medmastery_category')->onDelete('cascade');
            $table->integer('total_questions')->default(0)->comment('Total number of questions answered');
            $table->text('answer');
            $table->double('score')->nullable()->comment('Student score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('med_mastery_answers');
    }
};
