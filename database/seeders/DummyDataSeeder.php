<?php

namespace Database\Seeders;

use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Transaksi;
use App\Models\Aktivitas;
use App\Models\Pengumuman;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Kamar (30 data)
        for ($i = 1; $i <= 30; $i++) {
            $floor = floor(($i - 1) / 10) + 1;
            $roomNum = ($i % 10 == 0) ? $floor . '10' : $floor . '0' . ($i % 10);
            
            Kamar::updateOrCreate(
                ['nomor_kamar' => $roomNum],
                [
                    'harga_sewa' => $faker->randomElement([800000, 1000000, 1200000, 1500000, 2000000]),
                    'status' => 'Tersedia'
                ]
            );
        }

        $kamars = Kamar::all();

        // 2. Penghuni (25 data)
        $penghunis = [];
        $tempoOptions = [1, 1, 1, 2, 3, 6, 6, 12]; // weighted towards 1 month
        for ($i = 1; $i <= 25; $i++) {
            $kamar = $kamars->where('status', 'Tersedia')->random();
            $tglMasuk = $faker->dateTimeBetween('-1 year', '-1 month');
            $tglMasukCarbon = Carbon::instance($tglMasuk);
            $tempoPeriode = $faker->randomElement($tempoOptions);
            
            $penghuni = Penghuni::create([
                'id_kamar' => $kamar->id,
                'nama' => $faker->name,
                'nik' => $faker->numerify('################'),
                'password' => 'password123', // Model cast handles hashing
                'no_hp' => $faker->numerify('08##########'),
                'tgl_masuk' => $tglMasukCarbon->format('Y-m-d'),
                'tgl_jatuh_tempo' => $tglMasukCarbon->copy()->addMonths($faker->numberBetween(1, 12))->format('Y-m-d'),
                'jumlah_tagihan' => $kamar->harga_sewa,
                'tempo_periode' => $tempoPeriode,
                'status' => 'Aktif',
            ]);
            
            $kamar->update(['status' => 'Terisi']);
            $penghunis[] = $penghuni;
        }

        // 3. Transaksi (40 data)
        $statuses = ['Valid', 'Pending', 'Ditolak'];
        $metodes = ['Transfer BCA', 'Transfer Mandiri', 'OVO', 'Dana', 'Tunai'];
        
        for ($i = 1; $i <= 40; $i++) {
            $p = $faker->randomElement($penghunis);
            $createdAt = $faker->dateTimeBetween('-6 months', 'now');
            
            Transaksi::create([
                'id_penghuni' => $p->id,
                'bulan_tagihan' => Carbon::instance($createdAt)->translatedFormat('F Y'),
                'jumlah_bayar' => $p->jumlah_tagihan ?? $p->kamar->harga_sewa,
                'bukti_transfer' => 'sample_bukti.jpg',
                'tgl_bayar' => Carbon::instance($createdAt)->format('Y-m-d'),
                'metode_bayar' => $faker->randomElement($metodes),
                'status_validasi' => $faker->randomElement($statuses),
                'created_at' => $createdAt,
            ]);
        }

        // 4. Pengumuman (20 data)
        $ikons = ['fa-bullhorn', 'fa-circle-info', 'fa-triangle-exclamation', 'fa-calendar-days', 'fa-wrench', 'fa-credit-card'];
        $colors = [
            ['bg' => '#EFF6FF', 'icon' => '#3B82F6'], // Blue
            ['bg' => '#ECFDF5', 'icon' => '#10B981'], // Green
            ['bg' => '#FFFBEB', 'icon' => '#F59E0B'], // Yellow
            ['bg' => '#FEF2F2', 'icon' => '#EF4444'], // Red
        ];

        for ($i = 1; $i <= 20; $i++) {
            $color = $faker->randomElement($colors);
            Pengumuman::create([
                'judul' => $faker->sentence(4),
                'konten' => $faker->paragraph(3),
                'ikon' => $faker->randomElement($ikons),
                'warna_bg' => $color['bg'],
                'warna_ikon' => $color['icon'],
                'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
            ]);
        }

        // 5. Aktivitas (50 data)
        $tipes = ['Pembayaran', 'Validasi', 'Kamar', 'Sistem'];
        foreach (range(1, 50) as $index) {
            $p = $faker->randomElement($penghunis);
            $tipe = $faker->randomElement($tipes);
            
            $statusBadge = 'Info';
            $warnaBadge = 'badge-primary';
            
            if ($tipe == 'Pembayaran') {
                $statusBadge = 'Lunas';
                $warnaBadge = 'badge-success';
            } elseif ($tipe == 'Validasi') {
                $statusBadge = 'Menunggu';
                $warnaBadge = 'badge-warning';
            }

            Aktivitas::create([
                'id_penghuni' => $p->id,
                'judul' => $faker->words(3, true),
                'deskripsi' => $faker->sentence(),
                'tipe' => $tipe,
                'status_badge' => $statusBadge,
                'warna_badge' => $warnaBadge,
                'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
            ]);
        }

        // 6. Settings (defaults - will be seeded by migration, but re-seed to ensure)
        $defaults = [
            'fonnte_token' => '',
            'tempo_periode' => '1',
            'wa_reminder_enabled' => 'true',
            'wa_reminder_days_before' => '7',
            'wa_reminder_template' => 'Halo {nama}, kami dari pengelola KOSQU mengingatkan bahwa pembayaran sewa kamar {kamar} Anda sebesar Rp {tagihan} akan jatuh tempo pada {tanggal} ({sisa_hari} hari lagi). Mohon segera lakukan pembayaran. Terima kasih 🙏',
            'wa_overdue_template' => 'Halo {nama}, pembayaran sewa kamar {kamar} Anda sebesar Rp {tagihan} telah melewati jatuh tempo pada {tanggal}. Mohon segera lakukan pembayaran untuk menghindari denda. Terima kasih 🙏',
        ];

        foreach ($defaults as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
