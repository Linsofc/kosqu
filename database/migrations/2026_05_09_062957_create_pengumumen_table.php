<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('konten');
            $table->string('ikon')->default('fa-bullhorn');
            $table->string('warna_bg')->default('#EFF6FF');
            $table->string('warna_ikon')->default('#3B82F6');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pengumuman');
    }
};
