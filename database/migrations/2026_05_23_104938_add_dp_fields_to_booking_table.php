<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_booking', function (Blueprint $table) {
            $table->decimal('jumlah_dp', 12, 2)->default(0)->after('catatan');
            $table->enum('status_dp', ['Belum', 'Lunas'])->default('Belum')->after('jumlah_dp');
        });
    }

    public function down(): void
    {
        Schema::table('tb_booking', function (Blueprint $table) {
            $table->dropColumn(['jumlah_dp', 'status_dp']);
        });
    }
};
