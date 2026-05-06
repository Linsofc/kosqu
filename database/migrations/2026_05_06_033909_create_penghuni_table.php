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
        Schema::create('tb_penghuni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kamar')->constrained('tb_kamar')->onDelete('restrict');
            $table->string('nama', 100);
            $table->string('nik', 20)->unique();
            $table->string('no_hp', 15);
            $table->date('tgl_masuk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_penghuni');
    }
};
