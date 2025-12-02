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
        Schema::create('segmentation_access_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medmastery_segmentation_id')->constrained('medmastery_segmentation')->onDelete('cascade');
            $table->foreignId('access_role_id')->constrained('access_roles')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['medmastery_segmentation_id', 'access_role_id'], 'unique_segmentation_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segmentation_access_roles');
    }
};