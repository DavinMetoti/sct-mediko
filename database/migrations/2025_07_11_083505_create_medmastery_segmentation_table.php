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
        Schema::create('medmastery_segmentation', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Name of the segmentation');
            $table->text('description')->nullable()->comment('Description of the segmentation');
            $table->string('color')->default('#FFFFFF')->comment('Color associated with the segmentation');
            $table->unsignedBigInteger('created_by')->comment('User ID of the creator');
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->comment('Foreign key referencing the users table');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medmastery_segmentation');
    }
};
