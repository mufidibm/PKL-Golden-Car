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
        Schema::create('stok_opnames', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_inventory', ['barang', 'stok']);
            $table->unsignedBigInteger('item_id');
            $table->integer('stok_lama');
            $table->integer('stok_baru');
            $table->integer('selisih')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tanggal_opname');
            $table->timestamps();

            // Index untuk performa
            $table->index(['jenis_inventory', 'item_id']);
            $table->index('tanggal_opname');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_opnames');
    }
};
