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
        Schema::create('question_detail_flash_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_detail_id')
                ->constrained('question_details')
                ->onDelete('cascade');
            $table->string('path');
            $table->enum('panelist', ['0', '1','2', '-1', '-2'])->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_detail_flash_cards');
    }
};
