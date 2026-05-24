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
        Schema::table('tb_penghuni', function (Blueprint $table) {
            $table->unsignedTinyInteger('tempo_periode')->default(1)->after('jumlah_tagihan')
                  ->comment('Periode tempo pembayaran dalam bulan (1, 2, 3, 6, 12, dll)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_penghuni', function (Blueprint $table) {
            $table->dropColumn('tempo_periode');
        });
    }
};
