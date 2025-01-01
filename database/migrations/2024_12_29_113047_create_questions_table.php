<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('description')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->time('time')->nullable();
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('start_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
