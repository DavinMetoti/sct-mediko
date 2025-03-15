<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus constraint unique dari device_id
        Schema::table('user_devices', function (Blueprint $table) {
            $table->dropUnique('user_devices_device_id_unique'); // Hapus constraint unique
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tambahkan kembali constraint unique jika rollback
        Schema::table('user_devices', function (Blueprint $table) {
            $table->unique('device_id');
        });
    }
};
