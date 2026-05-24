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
        Schema::table('tb_booking', function (Blueprint $table) {
            $table->integer('tempo_periode')->default(1)->after('status_dp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_booking', function (Blueprint $table) {
            $table->dropColumn('tempo_periode');
        });
    }
};
