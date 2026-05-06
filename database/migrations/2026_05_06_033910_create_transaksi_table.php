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
        Schema::create('tb_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penghuni')->constrained('tb_penghuni')->onDelete('cascade');
            $table->string('bulan_tagihan', 20);
            $table->decimal('jumlah_bayar', 12, 2);
            $table->string('bukti_transfer')->nullable();
            $table->enum('status_validasi', ['Pending', 'Valid', 'Ditolak'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_transaksi');
    }
};
