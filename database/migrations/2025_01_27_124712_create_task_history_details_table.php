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
        Schema::create('task_history_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_history_id')->constrained('task_histories')->onDelete('cascade');
            $table->foreignId('question_detail_id')->constrained('question_details')->onDelete('cascade');
            $table->integer('value')->nullable();
            $table->enum('status', ['mark', 'completed'])->default('completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_history_details');
    }
};
