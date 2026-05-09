<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_aktivitas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_penghuni')->nullable()->after('id');
            $table->foreign('id_penghuni')->references('id')->on('tb_penghuni')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tb_aktivitas', function (Blueprint $table) {
            $table->dropForeign(['id_penghuni']);
            $table->dropColumn('id_penghuni');
        });
    }
};
