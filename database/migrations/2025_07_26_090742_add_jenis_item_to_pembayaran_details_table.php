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
        Schema::table('pembayaran_details', function (Blueprint $table) {
            $table->string('jenis_item')->after('pembayaran_id');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran_details', function (Blueprint $table) {
            $table->dropColumn('jenis_item');
        });
    }
};
