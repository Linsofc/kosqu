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
        Schema::create('tb_settings', function (Blueprint $table) {
            $table->string('key', 100)->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        $defaults = [
            'fonnte_token' => '',
            'tempo_periode' => '1',
            'wa_reminder_enabled' => 'true',
            'wa_reminder_days_before' => '7',
            'wa_reminder_template' => 'Halo {nama}, kami dari pengelola KOSQU mengingatkan bahwa pembayaran sewa kamar {kamar} Anda sebesar Rp {tagihan} akan jatuh tempo pada {tanggal} ({sisa_hari} hari lagi). Mohon segera lakukan pembayaran. Terima kasih 🙏',
            'wa_overdue_template' => 'Halo {nama}, pembayaran sewa kamar {kamar} Anda sebesar Rp {tagihan} telah melewati jatuh tempo pada {tanggal}. Mohon segera lakukan pembayaran untuk menghindari denda. Terima kasih 🙏',
        ];

        foreach ($defaults as $key => $value) {
            \DB::table('tb_settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_settings');
    }
};
