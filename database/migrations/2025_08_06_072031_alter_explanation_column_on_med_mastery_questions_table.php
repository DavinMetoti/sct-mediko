<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterExplanationColumnOnMedMasteryQuestionsTable extends Migration
{
    public function up()
    {
        Schema::table('med_mastery_questions', function (Blueprint $table) {
            $table->longText('explanation')->change();
        });
    }

    public function down()
    {
        Schema::table('med_mastery_questions', function (Blueprint $table) {
            $table->text('explanation', 255)->change();
        });
    }
}
