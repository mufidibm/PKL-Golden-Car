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
        Schema::create('pengerjaan_jasa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengerjaan_servis_id')->constrained('pengerjaan_servis')->onDelete('cascade');
            $table->foreignId('jasa_id')->constrained('jasa')->onDelete('cascade');
            $table->integer('qty')->default(1);
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengerjaan_jasa');
    }
};