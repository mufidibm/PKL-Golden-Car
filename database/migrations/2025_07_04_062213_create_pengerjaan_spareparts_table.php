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
        Schema::create('pengerjaan_spareparts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengerjaan_servis_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('qty');
            $table->decimal('harga', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->foreign('pengerjaan_servis_id')->references('id')->on('pengerjaan_servis')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengerjaan_spareparts');
    }
};
