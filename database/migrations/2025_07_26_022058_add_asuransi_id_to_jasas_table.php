<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('jasa', function (Blueprint $table) {
            $table->foreignId('asuransi_id')
                ->nullable()
                ->constrained('asuransis')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jasa', function (Blueprint $table) {
            $table->dropForeign(['asuransi_id']);
            $table->dropColumn('asuransi_id');
        });
    }
};
