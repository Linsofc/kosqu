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
            $table->integer('jumlah_tagihan')->nullable()->after('tgl_jatuh_tempo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_penghuni', function (Blueprint $table) {
            $table->dropColumn('jumlah_tagihan');
        });
    }
};
