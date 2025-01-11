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
        Schema::table('question_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sub_topic')->nullable()->after('id_medical_field');
            $table->unsignedBigInteger('id_question_type')->nullable()->after('id_sub_topic');

            $table->foreign('id_sub_topic')->references('id')->on('sub_topics')->onDelete('cascade');
            $table->foreign('id_question_type')->references('id')->on('question_detail_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
