<?php

namespace Database\Seeders;

use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Transaksi;
use App\Models\Aktivitas;
use App\Models\Pengumuman;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DashboardSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kamar tambahan
        $rooms = [
            ['nomor_kamar' => '103', 'harga_sewa' => 1000000, 'status' => 'Tersedia'],
            ['nomor_kamar' => '104', 'harga_sewa' => 1500000, 'status' => 'Terisi'],
            ['nomor_kamar' => '201', 'harga_sewa' => 1200000, 'status' => 'Tersedia'],
            ['nomor_kamar' => '202', 'harga_sewa' => 1200000, 'status' => 'Terisi'],
            ['nomor_kamar' => '203', 'harga_sewa' => 1200000, 'status' => 'Tersedia'],
            ['nomor_kamar' => '303', 'harga_sewa' => 1000000, 'status' => 'Tersedia'],
        ];

        foreach ($rooms as $room) {
            Kamar::updateOrCreate(['nomor_kamar' => $room['nomor_kamar']], $room);
        }

        // 2. Penghuni tambahan
        $kamar104 = Kamar::where('nomor_kamar', '104')->first();
        $p1 = Penghuni::updateOrCreate(
            ['nik' => '3201234567890001'],
            [
                'id_kamar' => $kamar104->id,
                'nama' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567890',
                'tgl_masuk' => Carbon::now()->subMonths(3),
                'tgl_jatuh_tempo' => Carbon::now()->addDays(2),
                'status' => 'Aktif',
            ]
        );

        $kamar202 = Kamar::where('nomor_kamar', '202')->first();
        $p2 = Penghuni::updateOrCreate(
            ['nik' => '3201234567890002'],
            [
                'id_kamar' => $kamar202->id,
                'nama' => 'Siti Aminah',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567891',
                'tgl_masuk' => Carbon::now()->subMonths(1),
                'tgl_jatuh_tempo' => Carbon::now()->addDays(15),
                'status' => 'Aktif',
            ]
        );

        // Tambah Penghuni yang sudah keluar
        $kamar303 = Kamar::where('nomor_kamar', '303')->first();
        $p3 = Penghuni::updateOrCreate(
            ['nik' => '9999999999999999'],
            [
                'id_kamar' => $kamar303->id,
                'nama' => 'Mantan Penghuni',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567899',
                'tgl_masuk' => Carbon::now()->subYear(),
                'tgl_jatuh_tempo' => Carbon::now()->subMonths(6),
                'status' => 'Keluar',
            ]
        );

        // 3. Transaksi Contoh
        Transaksi::create([
            'id_penghuni' => $p1->id,
            'bulan_tagihan' => Carbon::now()->translatedFormat('F Y'),
            'jumlah_bayar' => 1500000,
            'bukti_transfer' => 'sample_bukti.jpg',
            'tgl_bayar' => Carbon::now()->subDays(2),
            'metode_bayar' => 'Transfer BCA',
            'status_validasi' => 'Valid',
            'created_at' => Carbon::now(),
        ]);

        // 4. Pengumuman
        Pengumuman::create([
            'judul' => 'Pemeliharaan AC Rutin',
            'konten' => 'Diberitahukan kepada seluruh penghuni bahwa pemeliharaan AC rutin akan dilakukan pada tanggal 12-14 November.',
            'ikon' => 'fa-wrench',
            'warna_bg' => '#EFF6FF',
            'warna_ikon' => '#3B82F6',
            'created_at' => Carbon::parse('2023-11-10'),
        ]);

        // 5. Aktivitas (Dihubungkan ke Penghuni)
        Aktivitas::create([
            'id_penghuni' => $p1->id,
            'judul' => 'Budi Santoso membayar sewa',
            'deskripsi' => 'Kamar 104 - Transfer Bank BCA',
            'tipe' => 'Pembayaran',
            'status_badge' => 'Lunas',
            'warna_badge' => 'badge-success',
            'created_at' => Carbon::now()->subMinutes(30),
        ]);

        Aktivitas::create([
            'id_penghuni' => $p2->id,
            'judul' => 'Validasi Pembayaran Baru',
            'deskripsi' => 'Siti Aminah - Kamar 202',
            'tipe' => 'Validasi',
            'status_badge' => 'Menunggu',
            'warna_badge' => 'badge-warning',
            'created_at' => Carbon::now()->subDay(),
        ]);
        
        Aktivitas::create([
            'id_penghuni' => $p3->id,
            'judul' => 'Mantan Penghuni telah keluar',
            'deskripsi' => 'Kamar 303 telah dikosongkan',
            'tipe' => 'Kamar',
            'status_badge' => 'Keluar',
            'warna_badge' => 'badge-danger',
            'created_at' => Carbon::now()->subMonths(6),
        ]);
    }
}
