<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_transaksi', function (Blueprint $table) {
            $table->string('periode_tagihan', 20)->nullable()->after('bulan_tagihan');
        });

        // Backfill existing records: set periode_tagihan = bulan_tagihan
        \DB::statement("UPDATE tb_transaksi SET periode_tagihan = bulan_tagihan WHERE periode_tagihan IS NULL");
    }

    public function down(): void
    {
        Schema::table('tb_transaksi', function (Blueprint $table) {
            $table->dropColumn('periode_tagihan');
        });
    }
};
