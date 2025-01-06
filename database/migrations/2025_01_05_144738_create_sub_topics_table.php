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
        Schema::create('sub_topics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_header_sub_topic');
            $table->string('name');
            $table->string('description');
            $table->string('path');
            $table->timestamps();

            $table->foreign('id_header_sub_topic')
                ->references('id')
                ->on('header_sub_topics')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_topics');
    }
};
