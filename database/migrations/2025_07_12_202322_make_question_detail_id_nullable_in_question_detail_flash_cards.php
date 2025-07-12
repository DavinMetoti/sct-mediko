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
        Schema::table('question_detail_flash_cards', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['question_detail_id']);
            
            // Make the column nullable
            $table->unsignedBigInteger('question_detail_id')->nullable()->change();
            
            // Add foreign key constraint back with nullable
            $table->foreign('question_detail_id')
                ->references('id')
                ->on('question_details')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_detail_flash_cards', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['question_detail_id']);
            
            // Make the column not nullable again
            $table->unsignedBigInteger('question_detail_id')->nullable(false)->change();
            
            // Add foreign key constraint back
            $table->foreign('question_detail_id')
                ->references('id')
                ->on('question_details')
                ->onDelete('cascade');
        });
    }
};
