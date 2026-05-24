<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index()
    {
        $penghuni = Auth::guard('penghuni')->user();
        $penghuni->load('kamar');
        $kamar = $penghuni->kamar;
        
        // Current month for default tagihan
        $currentMonth = Carbon::now()->translatedFormat('F Y');
        
        // Payment progress data
        $totalTagihan = $penghuni->totalTagihanPeriode();
        $totalDibayar = $penghuni->totalDibayarPeriode();
        $sisaTagihan = $penghuni->sisaTagihan();
        $statusPembayaran = $penghuni->statusPembayaran();
        $progressPersen = $penghuni->progressPembayaran();
        $isPaid = ($statusPembayaran === 'Lunas');

        // Bank account info from settings
        $bankAccounts = [
            [
                'bank' => \App\Models\Setting::get('bank_1_name', 'MANDIRI'),
                'number' => \App\Models\Setting::get('bank_1_number', '1234-5678-9012'),
                'holder' => \App\Models\Setting::get('bank_1_holder', 'a.n Wisma AAM KOSQU'),
            ],
            [
                'bank' => \App\Models\Setting::get('bank_2_name', 'BCA'),
                'number' => \App\Models\Setting::get('bank_2_number', '9876-5432-10'),
                'holder' => \App\Models\Setting::get('bank_2_holder', 'a.n Wisma AAM KOSQU'),
            ],
        ];

        return view('user.payment.index', compact(
            'penghuni', 'kamar', 'currentMonth', 'isPaid', 'bankAccounts',
            'totalTagihan', 'totalDibayar', 'sisaTagihan', 'statusPembayaran', 'progressPersen'
        ));
    }

    public function store(Request $request)
    {
        $penghuni = Auth::guard('penghuni')->user();
        $penghuni->load('kamar');

        $sisaTagihan = $penghuni->sisaTagihan();

        $request->validate([
            'bulan_tagihan' => 'required',
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_bayar' => 'required',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tgl_bayar' => 'required|date',
        ]);

        // Validate amount doesn't exceed remaining balance
        if ((int)$request->jumlah_bayar > $sisaTagihan && $sisaTagihan > 0) {
            return back()->with('error', 'Jumlah bayar melebihi sisa tagihan (Rp ' . number_format($sisaTagihan, 0, ',', '.') . ').')
                         ->withInput();
        }

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        Transaksi::create([
            'id_penghuni' => $penghuni->id,
            'bulan_tagihan' => $request->bulan_tagihan,
            'periode_tagihan' => $penghuni->periodeAktif(),
            'jumlah_bayar' => $request->jumlah_bayar,
            'bukti_transfer' => $path,
            'tgl_bayar' => $request->tgl_bayar,
            'metode_bayar' => $request->metode_bayar,
            'status_validasi' => 'Pending',
        ]);

        return redirect()->route('user.invoice')->with('success', 'Bukti pembayaran berhasil diunggah. Silakan tunggu verifikasi admin.');
    }
}
