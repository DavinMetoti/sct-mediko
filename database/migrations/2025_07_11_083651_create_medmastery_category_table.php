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
        Schema::create('medmastery_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medmastery_segmentation_id')->nullable()->comment('ID of the segmentation');
            $table->string('name')->comment('Name of the category');
            $table->text('description')->nullable()->comment('Description of the category');
            $table->longText('icon')->nullable()->comment('Icon associated with the category');
            $table->unsignedBigInteger('created_by')->comment('User ID of the creator');
            $table->timestamps();
        });

        // Add foreign key constraints after table creation
        Schema::table('medmastery_category', function (Blueprint $table) {
            $table->foreign('medmastery_segmentation_id')
                ->references('id')
                ->on('medmastery_segmentation')
                ->onDelete('set null')
                ->comment('Foreign key referencing the medmastery_segmentation table');
            
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->comment('Foreign key referencing the users table');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medmastery_category');
    }
};
