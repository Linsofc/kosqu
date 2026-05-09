<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_penghuni', function (Blueprint $table) {
            $table->enum('status', ['Aktif', 'Keluar'])->default('Aktif')->after('tgl_jatuh_tempo');
        });
    }

    public function down(): void
    {
        Schema::table('tb_penghuni', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
