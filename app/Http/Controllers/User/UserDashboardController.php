<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $penghuni = Auth::guard('penghuni')->user();
        $penghuni->load('kamar');
        $today = Carbon::today();

        // === Status Hunian ===
        $kamar = $penghuni->kamar;

        // === Jatuh Tempo Berikutnya ===
        $tglJatuhTempo = $penghuni->tgl_jatuh_tempo
            ? Carbon::parse($penghuni->tgl_jatuh_tempo)->startOfDay()
            : null;

        $sisaHari = $tglJatuhTempo
            ? (int) $today->diffInDays($tglJatuhTempo, false)
            : null;

        // === Status Pembayaran (Cumulative) ===
        $statusPembayaran = $penghuni->statusPembayaran();
        $sudahBayarBulanIni = ($statusPembayaran === 'Lunas');
        $totalTagihan = $penghuni->totalTagihanPeriode();
        $totalDibayar = $penghuni->totalDibayarPeriode();
        $sisaTagihan = $penghuni->sisaTagihan();
        $progressPersen = $penghuni->progressPembayaran();

        // === Tagihan Aktif (Pending terbaru) ===
        $tagihanAktif = Transaksi::where('id_penghuni', $penghuni->id)
            ->where('status_validasi', 'Pending')
            ->orderBy('created_at', 'desc')
            ->first();

        // === Riwayat Transaksi (5 terakhir) ===
        $riwayatTransaksi = Transaksi::where('id_penghuni', $penghuni->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // === Pengumuman ===
        $totalPengumuman = \App\Models\Pengumuman::count();
        $pengumumans = \App\Models\Pengumuman::orderBy('created_at', 'desc')->take(10)->get();

        return view('user.dashboard', compact(
            'penghuni',
            'kamar',
            'tglJatuhTempo',
            'sisaHari',
            'sudahBayarBulanIni',
            'statusPembayaran',
            'totalTagihan',
            'totalDibayar',
            'sisaTagihan',
            'progressPersen',
            'tagihanAktif',
            'riwayatTransaksi',
            'pengumumans',
            'totalPengumuman'
        ));
    }
}
