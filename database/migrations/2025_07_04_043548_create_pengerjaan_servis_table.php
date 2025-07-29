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
        Schema::create('pengerjaan_servis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_masuk_id');
            $table->unsignedBigInteger('mekanik_id')->nullable();
            $table->enum('status', [
                'Waiting',
                'Sedang Dikerjakan',
                'Menunggu Sparepart',
                'Pemeriksaan Akhir',
                'Selesai',
            ]);
            $table->text('catatan')->nullable();
            $table->timestamp('mulai')->nullable();
            $table->timestamp('selesai')->nullable();
            $table->timestamps();

            $table->foreign('transaksi_masuk_id')->references('id')->on('transaksi_masuk')->onDelete('cascade');
            $table->foreign('mekanik_id')->references('id')->on('pegawais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengerjaan_servis');
    }
};
