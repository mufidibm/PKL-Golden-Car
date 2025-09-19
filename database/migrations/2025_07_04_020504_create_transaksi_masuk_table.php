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
        Schema::create('transaksi_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['menunggu', 'sedang dikerjakan', 'menunggu sparepart', 'pemeriksaan akhir', 'selesai'])->default('menunggu');
            $table->date('waktu_masuk')->default(now());
            $table->text('keluhan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_masuk');
    }
};
