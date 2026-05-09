<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Penghuni;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // === Stat Cards ===
        $totalKamar = Kamar::count();
        $kamarTerisi = Kamar::where('status', 'Terisi')->count();
        $kamarTersedia = Kamar::where('status', 'Tersedia')->count();
        $bookingKamar = Kamar::where('status', 'Booking')->count();
        $totalPenghuni = Penghuni::count();
        $penghuniAktif = Penghuni::where('status', 'Aktif')->count();
        $penghuniKeluar = Penghuni::where('status', 'Keluar')->count();
        $validasiTertunda = Transaksi::where('status_validasi', 'Pending')->count();

        // Pendapatan bulan ini (transaksi Valid di bulan ini)
        $pendapatanBulanIni = Transaksi::where('status_validasi', 'Valid')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('jumlah_bayar');

        // Pendapatan bulan lalu (untuk perbandingan %)
        $pendapatanBulanLalu = Transaksi::where('status_validasi', 'Valid')
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->sum('jumlah_bayar');

        $persenPerubahan = $pendapatanBulanLalu > 0
            ? round(($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu * 100)
            : ($pendapatanBulanIni > 0 ? 100 : 0);

        // === Okupansi ===
        $okupansi = $totalKamar > 0 ? round($kamarTerisi / $totalKamar * 100) : 0;

        // === Jatuh Tempo (3 hari ke depan) ===
        $jatuhTempo = Penghuni::with('kamar')
            ->whereNotNull('tgl_jatuh_tempo')
            ->whereBetween('tgl_jatuh_tempo', [$now->toDateString(), $now->copy()->addDays(3)->toDateString()])
            ->orderBy('tgl_jatuh_tempo', 'asc')
            ->get();

        // === Aktivitas Terbaru (5 terakhir dari tb_aktivitas) ===
        $aktivitasTerbaru = \App\Models\Aktivitas::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalKamar',
            'kamarTerisi',
            'kamarTersedia',
            'bookingKamar',
            'totalPenghuni',
            'penghuniAktif',
            'penghuniKeluar',
            'validasiTertunda',
            'pendapatanBulanIni',
            'persenPerubahan',
            'okupansi',
            'jatuhTempo',
            'aktivitasTerbaru'
        ));
    }
}
