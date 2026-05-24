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
        Schema::create('tb_riwayat_kamar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penghuni');
            $table->unsignedBigInteger('id_kamar');
            $table->date('tgl_masuk');
            $table->date('tgl_keluar')->nullable();
            $table->timestamps();

            $table->foreign('id_penghuni')->references('id')->on('tb_penghuni')->onDelete('cascade');
            $table->foreign('id_kamar')->references('id')->on('tb_kamar')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_riwayat_kamar');
    }
};
