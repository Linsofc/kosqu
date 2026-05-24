<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $penghuni = Auth::guard('penghuni')->user();
        
        $invoices = Transaksi::where('id_penghuni', $penghuni->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.invoice.index', compact('penghuni', 'invoices'));
    }

    public function downloadPdf(Transaksi $transaksi)
    {
        $penghuni = Auth::guard('penghuni')->user();

        // Ensure the transaction belongs to the logged-in user
        if ($transaksi->id_penghuni !== $penghuni->id) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure the transaction is valid
        if ($transaksi->status_validasi !== 'Valid') {
            abort(400, 'Kwitansi hanya tersedia untuk transaksi yang sudah lunas/valid.');
        }

        $transaksi->load('penghuni.kamar');

        $pdf = Pdf::loadView('user.invoice.pdf', compact('transaksi'));
        
        // Return file as download
        return $pdf->download('Kwitansi_KOSQU_' . $transaksi->bulan_tagihan . '_' . $penghuni->nama . '.pdf');
    }
}
