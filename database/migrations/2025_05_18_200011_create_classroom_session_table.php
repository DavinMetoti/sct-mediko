<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('classroom_session', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classroom_id');
            $table->unsignedBigInteger('quiz_session_id');
            $table->timestamps();

            $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
            $table->foreign('quiz_session_id')->references('id')->on('quiz_sessions')->onDelete('cascade');

            $table->unique(['classroom_id', 'quiz_session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_session');
    }
};
