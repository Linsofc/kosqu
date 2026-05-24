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
        if ($request->ajax()) {
            $query = Transaksi::with(['penghuni.kamar'])->orderBy('created_at', 'desc');
            
            if ($request->filled('status') && $request->status !== 'Semua') {
                $query->where('status_validasi', $request->status);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('penghuni', function($p) use ($search) {
                        $p->where('nama', 'like', "%$search%");
                    })->orWhere('id', 'like', "%$search%");
                });
            }
            
            $transaksis = $query->paginate(10);
            
            return response()->json([
                'html' => view('admin.transaksi._table_rows', compact('transaksis'))->render(),
                'pagination' => (string) $transaksis->links(),
            ]);
        }

        $query = Transaksi::with(['penghuni.kamar'])->orderBy('created_at', 'desc');
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

        if ($request->status == 'Valid') {
            $penghuni = $transaksi->penghuni;
            $periode = $transaksi->periode_tagihan ?? $penghuni->periodeAktif();
            
            // Calculate CUMULATIVE total paid for this period (all Valid transactions)
            $totalDibayar = Transaksi::where('id_penghuni', $penghuni->id)
                ->where('status_validasi', 'Valid')
                ->where('periode_tagihan', $periode)
                ->sum('jumlah_bayar');

            // Calculate total tagihan for the period
            $hargaSewa = $penghuni->jumlah_tagihan ?? $penghuni->kamar->harga_sewa;
            $tempoPeriode = max(1, (int)($penghuni->tempo_periode ?? 1));
            $totalTagihan = $hargaSewa * $tempoPeriode;
            
            // Only extend jatuh tempo when cumulative payments >= total tagihan
            if ($totalDibayar >= $totalTagihan) {
                if ($penghuni->tgl_jatuh_tempo) {
                    $newTempo = \Carbon\Carbon::parse($penghuni->tgl_jatuh_tempo)->addMonths($tempoPeriode);
                } else {
                    $newTempo = \Carbon\Carbon::now()->addMonths($tempoPeriode);
                }

                $penghuni->update([
                    'tgl_jatuh_tempo' => $newTempo->format('Y-m-d')
                ]);
            }
        }

        // Send WA E-Receipt
        $fonnteService = new \App\Services\FonnteService();
        $fonnteService->sendTransactionReceipt($transaksi);

        // Log Aktivitas
        Aktivitas::create([
            'id_penghuni' => $transaksi->id_penghuni,
            'judul' => 'Verifikasi Pembayaran',
            'deskripsi' => "Admin telah memvalidasi pembayaran {$transaksi->penghuni->nama} status: {$request->status}.",
            'tipe' => 'Validasi',
            'status_badge' => $request->status,
            'warna_badge' => $request->status == 'Valid' ? 'badge-success' : 'badge-danger',
            'url_aksi' => route('transaksi.index', ['search' => $transaksi->penghuni->nama], false),
        ]);

        return redirect()->back()->with('success', "Status transaksi #TRX-{$transaksi->id} berhasil diubah menjadi {$request->status}.");
    }
}
