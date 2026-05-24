<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kamar')->constrained('tb_kamar')->onDelete('cascade');
            $table->string('nama', 100);
            $table->string('nik', 20);
            $table->string('no_hp', 15);
            $table->string('password')->nullable();
            $table->date('tgl_booking');
            $table->date('tgl_rencana_masuk');
            $table->text('catatan')->nullable();
            $table->enum('status', ['Pending', 'Dikonfirmasi', 'Dibatalkan'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_booking');
    }
};
