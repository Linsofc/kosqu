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
        Schema::create('tb_whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penghuni')->nullable();
            $table->string('no_hp');
            $table->text('pesan');
            $table->enum('status', ['Success', 'Failed']);
            $table->text('response_api')->nullable();
            $table->timestamps();

            $table->foreign('id_penghuni')->references('id')->on('tb_penghuni')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_whatsapp_logs');
    }
};
