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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_users');
            $table->enum('gender', ['L', 'P'])->comment('L: Laki-laki, P: Perempuan');
            $table->string('address');
            $table->date('dob');
            $table->string('univ');
            $table->string('grade');
            $table->timestamps();

            $table->foreign('id_users')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropForeign(['id_users']);
        });

        Schema::dropIfExists('user_details');
    }
};
