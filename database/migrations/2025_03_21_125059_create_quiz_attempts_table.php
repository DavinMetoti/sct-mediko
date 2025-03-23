<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('attempt_token', 36)->unique();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->decimal('score', 5, 2)->default(0); // Mengubah score menjadi decimal
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('quiz_sessions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('quiz_attempts');
    }
};
