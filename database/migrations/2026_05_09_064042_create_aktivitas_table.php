<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('tipe'); // e.g., 'Pembayaran', 'Kamar', 'Validasi'
            $table->string('status_badge')->nullable(); // e.g., 'Lunas', 'Kosong', 'Menunggu'
            $table->string('warna_badge')->default('badge-info'); // badge-success, badge-warning, etc.
            $table->string('url_aksi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_aktivitas');
    }
};
