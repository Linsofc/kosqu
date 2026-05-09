<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_transaksi', function (Blueprint $table) {
            $table->date('tgl_bayar')->nullable()->after('bukti_transfer');
            $table->string('metode_bayar', 50)->nullable()->after('tgl_bayar');
        });

        Schema::table('tb_penghuni', function (Blueprint $table) {
            $table->date('tgl_jatuh_tempo')->nullable()->after('tgl_masuk');
        });
    }

    public function down(): void
    {
        Schema::table('tb_transaksi', function (Blueprint $table) {
            $table->dropColumn(['tgl_bayar', 'metode_bayar']);
        });

        Schema::table('tb_penghuni', function (Blueprint $table) {
            $table->dropColumn('tgl_jatuh_tempo');
        });
    }
};
