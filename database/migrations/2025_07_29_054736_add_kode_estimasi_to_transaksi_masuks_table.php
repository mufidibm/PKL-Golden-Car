<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // migration file
    public function up(): void
    {
        Schema::table('transaksi_masuk', function (Blueprint $table) {
            $table->string('kode_estimasi')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_masuks', function (Blueprint $table) {
            //
        });
    }
};
