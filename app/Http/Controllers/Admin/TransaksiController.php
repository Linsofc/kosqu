<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Aktivitas;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['penghuni.kamar'])->orderBy('created_at', 'desc');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_validasi', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('penghuni', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%");
            })->orWhere('id', 'like', "%$search%");
        }

        $transaksis = $query->paginate(10);
        
        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function validasi(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'status' => 'required|in:Valid,Ditolak',
        ]);

        $transaksi->update([
            'status_validasi' => $request->status
        ]);

        // Jika valid, update tgl_jatuh_tempo penghuni (+1 bulan)
        if ($request->status == 'Valid') {
            $penghuni = $transaksi->penghuni;
            $currentTempo = $penghuni->tgl_jatuh_tempo ? \Carbon\Carbon::parse($penghuni->tgl_jatuh_tempo) : \Carbon\Carbon::now();
            
            // Jika jatuh tempo sudah lewat, mulai dari sekarang. Jika belum, tambahkan dari tgl tersebut.
            if ($currentTempo->isPast()) {
                $newTempo = \Carbon\Carbon::now()->addMonth();
            } else {
                $newTempo = $currentTempo->addMonth();
            }

            $penghuni->update([
                'tgl_jatuh_tempo' => $newTempo->format('Y-m-d')
            ]);
        }

        // Log Aktivitas
        Aktivitas::create([
            'id_penghuni' => $transaksi->id_penghuni,
            'judul' => 'Verifikasi Pembayaran',
            'deskripsi' => "Admin telah memvalidasi pembayaran {$transaksi->penghuni->nama} status: {$request->status}. Tanggal jatuh tempo diperbarui.",
            'tipe' => 'Validasi',
            'status_badge' => $request->status,
            'warna_badge' => $request->status == 'Valid' ? 'badge-success' : 'badge-danger',
        ]);

        return redirect()->back()->with('success', "Status transaksi #TRX-{$transaksi->id} berhasil diubah menjadi {$request->status}.");
    }
}
