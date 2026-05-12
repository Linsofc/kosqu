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
        $kamar = Kamar::find($penghuni->id_kamar);
        
        // Current month for default tagihan
        $currentMonth = Carbon::now()->translatedFormat('F Y');
        
        // Check if already paid for this month
        $isPaid = Transaksi::where('id_penghuni', $penghuni->id)
            ->where('bulan_tagihan', $currentMonth)
            ->whereIn('status_validasi', ['Pending', 'Valid'])
            ->exists();

        return view('user.payment.index', compact('penghuni', 'kamar', 'currentMonth', 'isPaid'));
    }

    public function store(Request $request)
    {
        $penghuni = Auth::guard('penghuni')->user();
        $kamar = Kamar::find($penghuni->id_kamar);

        $request->validate([
            'bulan_tagihan' => 'required',
            'jumlah_bayar' => 'required|numeric',
            'metode_bayar' => 'required',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tgl_bayar' => 'required|date',
        ]);

        // Check again for duplicate before saving
        $exists = Transaksi::where('id_penghuni', $penghuni->id)
            ->where('bulan_tagihan', $request->bulan_tagihan)
            ->whereIn('status_validasi', ['Pending', 'Valid'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Tagihan untuk bulan ini sudah dilaporkan atau sudah lunas.');
        }

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        Transaksi::create([
            'id_penghuni' => $penghuni->id,
            'bulan_tagihan' => $request->bulan_tagihan,
            'jumlah_bayar' => $request->jumlah_bayar,
            'bukti_transfer' => $path,
            'tgl_bayar' => $request->tgl_bayar,
            'metode_bayar' => $request->metode_bayar,
            'status_validasi' => 'Pending',
        ]);

        return redirect()->route('user.invoice')->with('success', 'Bukti pembayaran berhasil diunggah. Silakan tunggu verifikasi admin.');
    }
}
