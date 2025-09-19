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
        Schema::create('stoks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_stok', 50)->unique();
            $table->string('nama_stok');
            $table->integer('stok')->default(0);
            $table->enum('kategori', ['Bahan Paint', 'Bahan non Paint', 'Tools']);
            $table->integer('harga_beli')->default(0);
            $table->string('satuan', 20);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Index untuk performa
            $table->index(['kategori', 'stok']);
            $table->index('nama_stok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stoks');
    }
};