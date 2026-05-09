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
        $now = Carbon::now();

        // === Status Hunian ===
        $kamar = $penghuni->kamar;

        // === Jatuh Tempo Berikutnya ===
        $tglJatuhTempo = $penghuni->tgl_jatuh_tempo
            ? Carbon::parse($penghuni->tgl_jatuh_tempo)
            : null;

        $sisaHari = $tglJatuhTempo
            ? (int) $now->diffInDays($tglJatuhTempo, false)
            : null;

        // === Status Pembayaran Bulan Ini ===
        $bulanIni = $now->translatedFormat('F Y'); // e.g. "Mei 2026"

        $transaksiValid = Transaksi::where('id_penghuni', $penghuni->id)
            ->where('status_validasi', 'Valid')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->first();

        $sudahBayarBulanIni = $transaksiValid !== null;

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
        $pengumumans = \App\Models\Pengumuman::orderBy('created_at', 'desc')->get();

        return view('user.dashboard', compact(
            'penghuni',
            'kamar',
            'tglJatuhTempo',
            'sisaHari',
            'sudahBayarBulanIni',
            'tagihanAktif',
            'riwayatTransaksi',
            'pengumumans'
        ));
    }
}
