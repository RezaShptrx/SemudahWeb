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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status', 50)->default('menunggu_antrian')->change();
        });

        // Update existing records
        DB::table('orders')->where('status', 'antri')->update(['status' => 'menunggu_antrian']);
        DB::table('orders')->where('status', 'sudah_diambil')->update(['status' => 'selesai']);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status', 50)->default('antri')->change();
        });
    }
};
